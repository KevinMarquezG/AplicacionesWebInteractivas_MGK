<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\WalletTransaction;

class WalletController extends Controller
{
        /**
     * Mostrar el monedero del cliente.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $transactions = $user->walletTransactions()
            ->latest()
            ->paginate(10);

        return view('client.wallet.index', compact(
            'user',
            'transactions'
        ));
    }
    /**
     * Canjear la clave maestra para recibir NL Coins.
     */
    public function redeem(Request $request)
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'max:255'],
        ]);

        $masterKey = (string) config('wallet.master_key');

        // Verificar que exista una clave configurada.
        if ($masterKey === '') {
            abort(503, 'El sistema de recargas no está configurado.');
        }

        // Validar la clave introducida.
        if (!hash_equals($masterKey, $validated['key'])) {
            return back()
                ->withErrors(['key' => 'La clave introducida no es válida.'])
                ->withInput();
        }

        $user = $request->user();

        // Solo los clientes pueden utilizar el sistema de recargas.
        abort_unless($user->isClient(), 403);

        $amount = number_format(
            (float) config('wallet.recharge_amount', 100),
            2,
            '.',
            ''
        );

        // Actualizar el saldo y registrar el movimiento.
        DB::transaction(function () use ($user, $amount) {

            $user = $user->newQuery()
                ->whereKey($user->id)
                ->lockForUpdate()
                ->firstOrFail();

            $user->increment('wallet_balance', $amount);

            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'recharge',
                'amount' => $amount,
                'description' => 'Recarga mediante clave maestra',
            ]);
        });

        return back()->with(
            'success',
            '¡Recarga exitosa! Has recibido 100 NL Coins.'
        );
    }
}
