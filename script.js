document.addEventListener('DOMContentLoaded', () => {
    const gamesPerPage = 5;
    let currentPage = 1;

    function displayGames(page) {
        const gameSection = document.querySelector('.game-section');
        gameSection.innerHTML = '';

        const startIndex = (page - 1) * gamesPerPage;
        const endIndex = startIndex + gamesPerPage;
        const gamesToShow = games.slice(startIndex, endIndex);

        gamesToShow.forEach(game => {
            const gameCard = document.createElement('div');
            gameCard.classList.add('game-card');

            gameCard.innerHTML = `
                <img src="uploads/${game.gambar}" alt="${game.judul}">
                <h3>${game.judul}</h3>
                <p>${game.deskripsi}</p>
                <a href="game_detail.php?id=${game.id_game}" class="detail-link">View Details</a>
            `;

            gameSection.appendChild(gameCard);
        });

        displayPagination();
    }

    function displayPagination() {
        const pagination = document.querySelector('#pagination');
        pagination.innerHTML = '';

        const totalPages = Math.ceil(games.length / gamesPerPage);

        for (let i = 1; i <= totalPages; i++) {
            const pageLink = document.createElement('button');
            pageLink.textContent = i;
            pageLink.classList.add('pagination-button');

            if (i === currentPage) {
                pageLink.classList.add('active');
            }

            pageLink.addEventListener('click', () => {
                currentPage = i;
                displayGames(currentPage);
            });

            pagination.appendChild(pageLink);
        }
    }

    displayGames(currentPage);
});
