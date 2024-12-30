document.addEventListener('DOMContentLoaded', () => {
    const games = [
        { title: "Game Title 1", description: "Discover an epic adventure in the world of Game 1!", image: "game1.jpg" },
        { title: "Game Title 2", description: "Experience the thrill and excitement of Game 2.", image: "game2.jpg" },
        { title: "Game Title 3", description: "Join the battle and become a hero in Game 3.", image: "game3.jpg" },
        { title: "Game Title 4", description: "Explore the mysteries of Game 4.", image: "game4.jpg" },
        { title: "Game Title 5", description: "Embark on a journey in Game 5.", image: "game5.jpg" },
        { title: "Game Title 6", description: "Dive into the action of Game 6.", image: "game6.jpg" },
        { title: "Game Title 7", description: "Master the challenges in Game 7.", image: "game7.jpg" },
        { title: "Game Title 8", description: "Unleash your skills in Game 8.", image: "game8.jpg" },
        { title: "Game Title 9", description: "Conquer the realm in Game 9.", image: "game9.jpg" },
        { title: "Game Title 10", description: "Discover the secrets of Game 10.", image: "game10.jpg" },
    ];

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
                <img src="${game.image}" alt="${game.title}">
                <h3>${game.title}</h3>
                <p>${game.description}</p>
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
