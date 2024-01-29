<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theater Movies</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Theater Movies</h2>

<div id="movies-container">
    <div class="category" id="now-showing">
        <h3>Now Showing</h3>
        <!-- Movies now showing will be loaded here -->
    </div>
    <div class="category" id="next-change">
        <h3>Next Change</h3>
        <!-- Movies for the next change will be loaded here -->
    </div>
    <div class="category" id="coming-soon">
        <h3>Coming Soon</h3>
        <!-- Upcoming movies will be loaded here -->
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const moviesContainer = document.getElementById("movies-container");

    // Function to load movies from the server
    function loadMovies() {
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    try {
                        const moviesData = JSON.parse(xhr.responseText).movies;
                        categorizeMovies(moviesData);
                    } catch (error) {
                        console.error("Error parsing JSON:", error);
                    }
                } else {
                    console.error("Error loading movies:", xhr.status, xhr.statusText);
                }
            }
        };

        xhr.open("GET", "get_movies.php", true);
        xhr.send();
    }

    // Function to categorize movies based on release date
    function categorizeMovies(moviesData) {
        const nowShowing = document.getElementById("now-showing");
        const nextChange = document.getElementById("next-change");
        const comingSoon = document.getElementById("coming-soon");

        // Current date in the format YYYY-MM-DD
        const currentDate = new Date().toISOString().split('T')[0];

        moviesData.forEach(movie => {
            const releaseDate = movie.releaseDate;
            const category = getCategory(releaseDate, currentDate);

            const movieElement = createMovieElement(movie);

            switch (category) {
                case 'now-showing':
                    nowShowing.appendChild(movieElement);
                    break;
                case 'next-change':
                    nextChange.appendChild(movieElement);
                    break;
                case 'coming-soon':
                    comingSoon.appendChild(movieElement);
                    break;
                default:
                    break;
            }
        });
    }

    // Function to determine the category based on release date
    function getCategory(releaseDate, currentDate) {
        if (releaseDate <= currentDate) {
            return 'now-showing';
        } else if (releaseDate === currentDate) {
            return 'next-change';
        } else {
            return 'coming-soon';
        }
    }

    // Function to create a movie element
    function createMovieElement(movie) {
        const movieElement = document.createElement("div");
        movieElement.className = "movie";
        movieElement.innerHTML = `
            <h3>${movie.title}</h3>
            <a href="movie_detail.php?movie_id=${movie.id}">
            <img src="${movie.imageUrl || 'placeholder_image_url.jpg'}" alt="${movie.title} Poster"  class="movie-poster">
        `;

        return movieElement;
    }

    // Load movies on page load
    loadMovies();
});
</script>

</body>
</html>
