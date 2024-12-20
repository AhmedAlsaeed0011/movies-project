<template>
    <div class="container mt-5">
      <h1 class="text-center mb-4">Movie Management</h1>
      <div class="row">
        <!-- Movie List -->
        <div class="col-md-8">
          <h3>Movie List</h3>
          <input
            type="text"
            v-model="searchQuery"
            class="form-control mb-3"
            placeholder="Search movies..."
          />
          <ul class="list-group">
            <li
              v-for="movie in filteredMovies"
              :key="movie.id"
              class="list-group-item d-flex justify-content-between align-items-center"
            >
              <div>
                <strong>{{ movie.title }}</strong> - {{ movie.year }}
                <p class="mb-0">{{ movie.description }}</p>
              </div>
              <div>
                <button
                  @click="editMovie(movie)"
                  class="btn btn-warning btn-sm me-2"
                >
                  Update
                </button>
                <button
                  @click="deleteMovie(movie.id)"
                  class="btn btn-danger btn-sm"
                >
                  Delete
                </button>
              </div>
            </li>
          </ul>
        </div>
  
        <!-- Add/Edit Movie Form -->
        <div class="col-md-4">
          <h3>{{ isEditing ? "Edit Movie" : "Add Movie" }}</h3>
          <div v-if="errors.length" class="alert alert-danger">
            <ul>
              <li v-for="error in errors" :key="error">{{ error }}</li>
            </ul>
          </div>
          <form @submit.prevent="isEditing ? updateMovie() : addMovie()">
            <div class="mb-3">
              <label for="title" class="form-label">Title</label>
              <input
                v-model="selectedMovie.title"
                type="text"
                id="title"
                class="form-control"
                placeholder="Enter movie title"
              />
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <textarea
                v-model="selectedMovie.description"
                id="description"
                class="form-control"
                placeholder="Enter description"
              ></textarea>
            </div>
            <div class="mb-3">
              <label for="year" class="form-label">Year</label>
              <input
                v-model="selectedMovie.year"
                type="number"
                id="year"
                class="form-control"
                placeholder="Enter year"
              />
            </div>
            <button type="submit" class="btn btn-primary w-100">
              {{ isEditing ? "Update Movie" : "Add Movie" }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  
  export default {
    data() {
      return {
        movies: [],
        selectedMovie: {
          id: null,
          title: "",
          description: "",
          year: null,
        },
        isEditing: false,
        errors: [],
        searchQuery: "",
      };
    },
    computed: {
      filteredMovies() {
        return this.movies.filter((movie) =>
          movie.title.toLowerCase().includes(this.searchQuery.toLowerCase())
        );
      },
    },
    methods: {
      loadMovies() {
        axios
          .get("/api/movies")
          .then((response) => {
            this.movies = response.data;
          })
          .catch((error) => {
            console.error("Error fetching movies:", error);
          });
      },
      addMovie() {
        axios
          .post("/api/movies", this.selectedMovie)
          .then(() => {
            alert("Movie added successfully!");
            this.loadMovies();
            this.resetForm();
          })
          .catch((error) => {
            if (error.response && error.response.data.errors) {
              this.errors = Object.values(error.response.data.errors).flat();
            }
          });
      },
      editMovie(movie) {
        this.selectedMovie = { ...movie };
        this.isEditing = true;
        this.errors = [];
      },
      updateMovie() {
        axios
          .put(`/api/movies/${this.selectedMovie.id}`, this.selectedMovie)
          .then(() => {
            alert("Movie updated successfully!");
            this.loadMovies();
            this.resetForm();
          })
          .catch((error) => {
            if (error.response && error.response.data.errors) {
              this.errors = Object.values(error.response.data.errors).flat();
            }
          });
      },
      deleteMovie(id) {
        if (confirm("Are you sure you want to delete this movie?")) {
          axios
            .delete(`/api/movies/${id}`)
            .then(() => {
              alert("Movie deleted successfully!");
              this.loadMovies();
            })
            .catch((error) => {
              console.error("Error deleting movie:", error);
            });
        }
      },
      resetForm() {
        this.selectedMovie = {
          id: null,
          title: "",
          description: "",
          year: null,
        };
        this.isEditing = false;
        this.errors = [];
      },
    },
    mounted() {
      this.loadMovies();
    },
  };
  </script>
  
  <style>
  body {
    background-color: #f8f9fa;
  }
  </style>
  