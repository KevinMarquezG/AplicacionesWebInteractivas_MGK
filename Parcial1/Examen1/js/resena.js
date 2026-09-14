$(document).ready(function () {
    let favorites = JSON.parse(localStorage.getItem("favorites")) || [];
    $(".fav-badge").text(favorites.length);

    // Obtener ID desde los parámetros de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const movieId = urlParams.get("id");

    function renderStars(rating) {
        const fullStars = Math.round(parseFloat(rating) || 0);
        let stars = "";
        for (let i = 1; i <= 10; i++) {
            stars += i <= fullStars 
                ? '<i class="bi bi-star-fill text-warning"></i> ' 
                : '<i class="bi bi-star text-warning"></i> ';
        }
        return stars;
    }

    // Formatear montos de dinero (USD)
    function formatCurrency(amount) {
        if (!amount) return "N/A";
        return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', maximumFractionDigits: 0 }).format(amount);
    }

    // Cargar JSON
    $.getJSON("PELICULAS.json")
        .done(function (movies) {
            const movie = movies.find(m => m.id === movieId);

            if (!movie) {
                $("#detail-content").html(`
                    <div class="col-12 text-center py-5">
                        <h4 class="text-danger">Película no encontrada</h4>
                        <a href="index.html" class="btn btn-primary mt-3">Volver al inicio</a>
                    </div>
                `);
                return;
            }

            const isFav = favorites.includes(movie.id);
            const posterImg = movie.primaryImage || (movie.thumbnails && movie.thumbnails[1] ? movie.thumbnails[1].url : '');
            
            // Unir compañías productoras
            const producers = movie.productionCompanies && movie.productionCompanies.length > 0 
                ? movie.productionCompanies.map(p => p.name).join(", ") 
                : "N/A";

            // Unir idiomas y países
            const languages = movie.spokenLanguages ? movie.spokenLanguages.join(", ").toUpperCase() : "N/A";
            const countries = movie.countriesOfOrigin ? movie.countriesOfOrigin.join(", ") : "N/A";

            const detailHtml = `
                <!-- Columna Izquierda: Póster -->
                <div class="col-md-4 text-center">
                    <img src="${posterImg}" alt="${movie.primaryTitle}" class="img-fluid rounded-3 shadow-sm w-100" style="max-height: 520px; object-fit: cover;">
                </div>

                <!-- Columna Derecha: Metadatos -->
                <div class="col-md-8 d-flex flex-column justify-content-between">
                    <div>
                        <!-- Título y botón favorito -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h2 class="fw-bold mb-0">${movie.primaryTitle}</h2>
                            <button class="btn btn-outline-danger btn-sm rounded-pill px-3" id="fav-detail-btn" data-id="${movie.id}">
                                <i class="bi ${isFav ? 'bi-heart-fill' : 'bi-heart'} me-1"></i> Favorito
                            </button>
                        </div>

                        <!-- Metadatos rápidos -->
                        <div class="d-flex gap-3 text-muted small mb-3 align-items-center flex-wrap">
                            <span><i class="bi bi-calendar3 me-1"></i> ${movie.startYear || 'N/A'}</span>
                            <span><i class="bi bi-clock me-1"></i> ${movie.runtimeMinutes ? movie.runtimeMinutes + ' min' : 'N/A'}</span>
                            <span class="badge bg-light text-dark border"><i class="bi bi-file-earmark-lock2 me-1"></i> ${movie.contentRating || 'N/A'}</span>
                        </div>

                        <!-- Calificación -->
                        <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                            <span class="fs-4 fw-bold text-warning">${movie.averageRating || '0.0'}</span>
                            <div class="small">${renderStars(movie.averageRating)}</div>
                            <span class="text-muted small">(${movie.numVotes ? movie.numVotes.toLocaleString() : 0} votos)</span>
                        </div>

                        <!-- Géneros -->
                        <div class="mb-3">
                            <h6 class="fw-semibold small text-muted mb-1">Géneros</h6>
                            <div class="d-flex gap-2 flex-wrap">
                                ${(movie.genres || []).map(g => `<span class="badge bg-primary-subtle text-primary border border-primary-subtle">${g}</span>`).join('')}
                            </div>
                        </div>

                        <!-- Cita / Descripción destacada -->
                        <div class="p-3 my-3 quote-box rounded">
                            "${movie.description || 'Sin descripción disponible.'}"
                        </div>

                        <!-- Intereses -->
                        ${movie.interests && movie.interests.length > 0 ? `
                        <div class="mb-3">
                            <h6 class="fw-semibold small text-muted mb-1">Intereses</h6>
                            <div class="d-flex gap-1 flex-wrap">
                                ${movie.interests.map(item => `<span class="badge bg-light text-secondary border">${item}</span>`).join('')}
                            </div>
                        </div>` : ''}

                        <!-- Ficha Técnica -->
                        <div class="row g-2 small text-muted border-top pt-3 mt-3">
                            <div class="col-6 col-md-3"><strong>Idiomas:</strong></div>
                            <div class="col-6 col-md-3 text-dark">${languages}</div>
                            <div class="col-6 col-md-3"><strong>Países:</strong></div>
                            <div class="col-6 col-md-3 text-dark">${countries}</div>

                            <div class="col-6 col-md-3"><strong>Presupuesto:</strong></div>
                            <div class="col-6 col-md-3 text-dark">${formatCurrency(movie.budget)}</div>
                            <div class="col-6 col-md-3"><strong>Recaudación:</strong></div>
                            <div class="col-6 col-md-3 text-dark">${formatCurrency(movie.grossWorldwide)}</div>

                            <div class="col-6 col-md-3"><strong>Productoras:</strong></div>
                            <div class="col-6 col-md-9 text-dark">${producers}</div>
                        </div>
                    </div>

                    <!-- Botones inferiores y enlaces externos -->
                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex gap-2 mb-3">
                            ${movie.trailer ? `
                                <a href="${movie.trailer}" target="_blank" class="btn btn-danger btn-sm px-3 rounded-pill">
                                    <i class="bi bi-play-fill me-1"></i> Ver tráiler
                                </a>` : ''}
                            <a href="index.html" class="btn btn-light border btn-sm px-3 rounded-pill text-muted">
                                <i class="bi bi-arrow-left me-1"></i> Volver
                            </a>
                        </div>
                        
                        ${movie.externalLinks && movie.externalLinks.length > 0 ? `
                        <div>
                            <span class="small text-muted d-block mb-1">Enlaces externos:</span>
                            <div class="d-flex gap-2 flex-wrap">
                                ${movie.externalLinks.map(link => {
                                    const domain = new URL(link).hostname.replace('www.', '');
                                    return `<a href="${link}" target="_blank" class="badge bg-light text-secondary text-decoration-none border">${domain}</a>`;
                                }).join('')}
                            </div>
                        </div>` : ''}
                    </div>
                </div>
            `;

            $("#detail-content").html(detailHtml);

            // Handler de Favoritos en la vista de detalle
            $("#fav-detail-btn").on("click", function () {
                if (favorites.includes(movie.id)) {
                    favorites = favorites.filter(id => id !== movie.id);
                    $(this).html('<i class="bi bi-heart me-1"></i> Favorito');
                } else {
                    favorites.push(movie.id);
                    $(this).html('<i class="bi bi-heart-fill me-1"></i> Favorito');
                }
                localStorage.setItem("favorites", JSON.stringify(favorites));
                $(".fav-badge").text(favorites.length);
            });
        })
        .fail(function () {
            $("#detail-content").html(`
                <div class="col-12 alert alert-danger text-center">
                    Error al cargar los datos desde <strong>PELICULAS.json</strong>.
                </div>
            `);
        });
});