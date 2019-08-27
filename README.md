# GameSnitch (WIP) is my pet project video game search engine. I wanted
to create a search engine that was dedicated to video games, and only
pulled up the most pertinent information I sought. Here's how it 
works:
1.) A user searches for a game by title (partial matches are allowed).
2.) The backend script(query.php) first attempts to find any entries matching that
title in the local database.
3.) If there are matches in the database, the results are listed with 
cover art and titles.
  
4.) If there are no matches in the local database, the backend will make an
API call to IGDB requesting that title, and any matches found will be
added to the local database. 
  4a.) Once the API call is complete, the backend script will make a second
  attempt to query the local database.
  4b.) If there are now matches, the search query will be resubmitted,
  refreshing the page and showing the results.
  4c.) If there are still no matches, the user is informed that no results
  were found matching that item.
  
  
The current version successfully displays 5 results from the local DB.


Once core scripting is finished, the results will no longer be limited to 5,
and pagination will be included. Also, the results listed will be clickable,
with each link propogating a profile page with all info for that title.

CSS will be adjusted last.
