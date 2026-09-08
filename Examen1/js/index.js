$(document).ready(function () {
    let allMovies = [];
    let favorites = JSON.parse(localStorage.getItem("favorites")) || [];
    let currentFilter = "all";

    function updateFavCounter() {
        $(".fav-badge").text(favorites.length);
    }

    // Genera 10 estrellas visuales redondeando la calificación
    function renderStars(rating) {
        const score = parseFloat(rating) || 0;
        const fullStars = Math.round(score);
        let starsHtml = "";
        for (let i = 1; i <= 10; i++) {
            starsHtml += i <= fullStars 
                ? '<i class="bi bi-star-fill text-warning"></i>' 
                : '<i class="bi bi-star text-warning"></i>';
        }
        return starsHtml;
    }

    function renderCards(movies) {
        const $container = $("#movies-container");
        $container.empty();

        if (movies.length === 0) {
            $container.html('<div class="col-12 text-center text-muted py-5">No se encontraron películas para este criterio.</div>');
            return;
        }

        movies.forEach(movie => {
            const isFav = favorites.includes(movie.id);
            const favIconClass = isFav ? "bi-heart-fill text-danger" : "bi-heart text-muted";
            const posterImg = movie.primaryImage || (movie.thumbnails && movie.thumbnails[1] ? movie.thumbnails[1].url : 'https://via.placeholder.com/300x450?text=Sin+Imagen');

            const cardHtml = `
                <div class="col">
                    <div class="card movie-card h-100 shadow-sm border-0 rounded-3">
                        <div class="movie-poster-wrapper" style="height: 360px; background-color: #000; overflow: hidden;">
                            <img src="${posterImg}" alt="${movie.primaryTitle}" class="w-100 h-100 object-fit-cover">
                        </div>
                        <div class="card-body p-3 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="card-title fw-bold mb-0 text-truncate" title="${movie.primaryTitle}">
                                        ${movie.primaryTitle}
                                    </h6>
                                    <button class="btn btn-link p-0 text-decoration-none btn-toggle-fav" data-id="${movie.id}">
                                        <i class="bi ${favIconClass}"></i>
                                    </button>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small">${movie.startYear || 'N/A'}</span>
                                    <span class="text-warning fw-bold small">
                                        ${movie.averageRating || 'N/A'} <i class="bi bi-star-fill small"></i>
                                    </span>
                                </div>
                                <div class="stars-rating mb-3 small">
                                    ${renderStars(movie.averageRating)}
                                </div>
                            </div>
                            <a href="resena.html?id=${movie.id}" class="btn btn-primary btn-sm w-100 rounded-pill py-1">
                                <i class="bi bi-eye me-1"></i> Ver reseña
                            </a>
                        </div>
                    </div>
                </div>
            `;
            $container.append(cardHtml);
        });
    }

    function applyFilters() {
        const searchTerm = $("#search-input").val().toLowerCase().trim();

        const filtered = allMovies.filter(movie => {
            const matchesSearch = movie.primaryTitle.toLowerCase().includes(searchTerm);

            let matchesYear = true;
            if (currentFilter !== "all") {
                const [min, max] = currentFilter.split("-").map(Number);
                matchesYear = movie.startYear >= min && movie.startYear <= max;
            }

            return matchesSearch && matchesYear;
        });

        renderCards(filtered);
    }

    // 1. Cargar el archivo PELICULAS.json
    $.getJSON("PELICULAS.json")
        .done(function (data) {
            allMovies = data;
            updateFavCounter();
            renderCards(allMovies);
        })
        .fail(function (jqxhr, textStatus, error) {
            console.error("Error cargando PELICULAS.json:", textStatus, error);
            $("#movies-container").html(`
                <div class="col-12 alert alert-danger text-center">
                    No se pudo cargar el archivo <strong>PELICULAS.json</strong>. Asegúrate de ejecutarlo sobre un servidor local.
                </div>
            `);
        });

    // 2. Eventos de filtrado por rango de años
    $(".btn-filter").on("click", function () {
        $(".btn-filter").removeClass("active");
        $(this).addClass("active");
        currentFilter = $(this).data("range");
        applyFilters();
    });

    // 3. Evento de búsqueda por teclado
    $("#search-input").on("input", function () {
        applyFilters();
    });

    // 4. Toggle Favoritos
    $(document).on("click", ".btn-toggle-fav", function (e) {
        e.preventDefault();
        const id = $(this).data("id");

        if (favorites.includes(id)) {
            favorites = favorites.filter(favId => favId !== id);
        } else {
            favorites.push(id);
        }

        localStorage.setItem("favorites", JSON.stringify(favorites));
        updateFavCounter();
        applyFilters();
    });
});