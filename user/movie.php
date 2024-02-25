<?php
// include("../env+session.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theater Movies</title>
    <link rel="stylesheet" href="../styles/reset.css">
    <link rel="stylesheet" href="../styles/homepage.css">
</head>

<body>
    <h2 class="header">Theater Movies</h2>

    <div id="movies-container" class="container">

        <section class="page-section">
            <h3 class="section-title">Now Showing</h3>
            <div class="category" id="now-showing">
                <!-- Movies now showing will be loaded here -->
            </div>
        </section>

        <section class="page-section">
            <h3 class="section-title">Next Change</h3>
            <div class="category" id="next-change">
                <!-- Movies for the next change will be loaded here -->
            </div>
        </section>

        <section class="page-section">
            <h3 class="section-title">Coming Soon</h3>
            <div class="category" id="coming-soon">
                <!-- Upcoming movies will be loaded here -->
            </div>
        </section>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const moviesContainer = document.getElementById("movies-container");

            // Function to load movies from the server
            function loadMovies() {
                const xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
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

                const oneWeek = new Date(currentDate);
                oneWeek.setDate(oneWeek.getDate() - 7);

                moviesData.forEach(movie => {
                    const releaseDate = movie.releaseDate;
                    const category = getCategory(releaseDate, currentDate, oneWeek);

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
            function getCategory(releaseDate, currentDate, oneWeekBefore) {
                const sevenDaysAfter = new Date(currentDate);
                sevenDaysAfter.setDate(currentDate.getDate() + 7);

                if (releaseDate >= oneWeekBefore && releaseDate <= currentDate) {
                    return 'now-showing';
                } else if (releaseDate > currentDate && releaseDate <= sevenDaysAfter) {
                    return 'next-change';
                } else if (releaseDate > sevenDaysAfter) {
                    return 'coming-soon';
                } else {
                    return 'coming-soon'; // You might want to handle other cases explicitly
                }
            }

            // Function to create a movie element
            function createMovieElement(movie) {
                const movieElement = document.createElement("div");
                movieElement.className = "movie";
                movieElement.innerHTML = `
                    <h3>${movie.title}</h3>
                    <a href="movie_detail.php?movie_id=${movie.id}">
                        <img src="${movie.imageUrl || 'placeholder_image_url.jpg'}" alt="${movie.title} Poster" class="movie-poster">
                    </a>`;

                return movieElement;
            }

            // Load movies on page load
            loadMovies();
        });
    </script>

</body>

</html>
