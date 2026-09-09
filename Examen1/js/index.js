$(document).ready(function () {
    let allMovies = [];
    let favorites = JSON.parse(localStorage.getItem("favorites")) || [];
    let currentFilter = "all";
    let showOnlyFavorites = false;

    function updateFavCounter() {
        $(".fav-badge").text(favorites.length);
    }

    function renderStars(rating) {
        const fullStars = Math.round(parseFloat(rating) || 0);
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

        if (!movies || movies.length === 0) {
            const emptyMsg = showOnlyFavorites 
                ? 'No tienes películas guardadas en favoritos todavía. ¡Haz clic en el corazón de cualquier tarjeta para agregarla!'
                : 'No se encontraron películas para los filtros seleccionados.';

            $container.html(`
                <div class="col-12 text-center text-muted py-5">
                    <i class="bi ${showOnlyFavorites ? 'bi-heartbreak' : 'bi-camera-reels'} fs-1 d-block mb-2 text-danger"></i>
                    <p class="mb-3">${emptyMsg}</p>
                    ${showOnlyFavorites ? '<button class="btn btn-outline-primary btn-sm rounded-pill" id="btn-back-catalog">Volver al catálogo completo</button>' : ''}
                </div>
            `);
            return;
        }

        movies.forEach(movie => {
            const isFav = favorites.includes(movie.id);
            const favIconClass = isFav ? "bi-heart-fill text-danger" : "bi-heart text-muted";
            
            let posterImg = 'https://via.placeholder.com/300x450?text=Sin+Imagen';
            if (movie.primaryImage) {
                posterImg = movie.primaryImage;
            } else if (movie.thumbnails && movie.thumbnails.length > 0) {
                posterImg = movie.thumbnails[movie.thumbnails.length - 1].url;
            }

            const title = movie.primaryTitle || movie.originalTitle || "Sin título";
            const year = movie.startYear || "N/A";
            const rating = movie.averageRating ? movie.averageRating.toFixed(1) : "N/A";

            const cardHtml = `
                <div class="col">
                    <div class="card movie-card h-100 shadow-sm border-0 rounded-3">
                        <div class="movie-poster-wrapper" style="height: 380px; background-color: #000; overflow: hidden;">
                            <img src="${posterImg}" alt="${title}" class="w-100 h-100 object-fit-cover" onerror="this.src='https://via.placeholder.com/300x450?text=Sin+Imagen'">
                        </div>
                        <div class="card-body p-3 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="card-title fw-bold mb-0 text-truncate" title="${title}">
                                        ${title}
                                    </h6>
                                    <button class="btn btn-link p-0 text-decoration-none btn-toggle-fav" data-id="${movie.id}" title="Favorito">
                                        <i class="bi ${favIconClass}"></i>
                                    </button>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small">${year}</span>
                                    <span class="text-warning fw-bold small">
                                        ${rating} <i class="bi bi-star-fill small"></i>
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

   
    // FUNCIÓN CENTRAL DE FILTRADO
    function applyFilters() {
        const searchTerm = ($("#search-input").val() || "").toLowerCase().trim();

        const filtered = allMovies.filter(movie => {
            // 1. Filtro si está activo el modo favoritos
            if (showOnlyFavorites && !favorites.includes(movie.id)) {
                return false;
            }

            // 2. Coincidencia con la barra de búsqueda (por título)
            const title = (movie.primaryTitle || movie.originalTitle || "").toLowerCase();
            const matchesSearch = title.includes(searchTerm);

            // 3. Coincidencia con el rango de años
            let matchesYear = true;
            if (currentFilter !== "all") {
                const year = parseInt(movie.startYear, 10);
                const [minYear, maxYear] = currentFilter.split("-").map(Number);

                if (isNaN(year)) {
                    matchesYear = false;
                } else {
                    matchesYear = year >= minYear && year <= maxYear;
                }
            }

            return matchesSearch && matchesYear;
        });

        renderCards(filtered);
    }

    // Actualiza el título y subtítulo según la vista actual
    function updatePageHeader() {
        if (showOnlyFavorites) {
            $(".main-title").html('<i class="bi bi-heart-fill text-danger"></i> Mis Películas <span class="highlight">Favoritas</span>');
            $(".main-title").next("p").text("Tus títulos guardados para ver o consultar");
            $("#view-favorites").addClass("active text-warning fw-bold");
        } else {
            $(".main-title").html('<i class="bi bi-film text-primary"></i> Películas <span class="highlight">Destacadas</span>');
            $(".main-title").next("p").text("Las mejores películas según la crítica y el público");
            $("#view-favorites").removeClass("active text-warning fw-bold");
        }
    }

    // EVENTO: CLIC EN "FAVORITOS" DEL HEADER
    $("#view-favorites").on("click", function (e) {
        e.preventDefault();
        showOnlyFavorites = true;

        // Reset de botón de años a "Todos" para mostrar todos los favoritos guardados
        $(".btn-filter").removeClass("active");
        $('.btn-filter[data-range="all"]').addClass("active");
        currentFilter = "all";

        updatePageHeader();
        applyFilters();
    });

    // EVENTO: CLIC EN "INICIO" DEL HEADER
    $("#nav-home, #btn-back-catalog").on("click", function (e) {
        e.preventDefault();
        showOnlyFavorites = false;
        $("#search-input").val("");
        
        $(".btn-filter").removeClass("active");
        $('.btn-filter[data-range="all"]').addClass("active");
        currentFilter = "all";

        updatePageHeader();
        applyFilters();
    });

    // Evento: Botón para volver al catálogo cuando no hay favoritos
    $(document).on("click", "#btn-back-catalog", function (e) {
        e.preventDefault();
        showOnlyFavorites = false;
        updatePageHeader();
        applyFilters();
    });

    // EVENTO: BOTONES DE FILTRO DE AÑOS
    $(document).on("click", ".btn-filter", function (e) {
        e.preventDefault();
        $(".btn-filter").removeClass("active");
        $(this).addClass("active");
        currentFilter = $(this).attr("data-range");
        applyFilters();
    });

    // BÚSQUEDA EN TIEMPO REAL
    $("#search-input").on("input", function () {
        applyFilters();
    });

    $("#search-form").on("submit", function (e) {
        e.preventDefault();
        applyFilters();
    });

    // ==========================================
    // AGREGAR / REMOVER FAVORITOS
    // ==========================================
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
        applyFilters(); // Re-renderiza al instante (si estás en favoritos, se removerá la tarjeta)
    });

    // CARGAR JSON INICIAL
    $.getJSON("PELICULAS.json")
        .done(function (data) {
            allMovies = data;
            updateFavCounter();
            applyFilters();
        })
        .fail(function (jqxhr, textStatus, error) {
            console.error("Error al cargar PELICULAS.json:", textStatus, error);
            $("#movies-container").html(`
                <div class="col-12 alert alert-danger text-center">
                    Error cargando <strong>PELICULAS.json</strong>.
                </div>
            `);
        });
});