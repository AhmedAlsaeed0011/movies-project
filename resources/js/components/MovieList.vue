<template>
    <div>
      <h1>Movie Management</h1>
  
      <h2>Movie List</h2>
      <ul>
        <li v-for="movie in movies" :key="movie.id">
          {{ movie.title }} - {{ movie.year }}
          <button @click="editMovie(movie)">Update</button>
          <button @click="deleteMovie(movie.id)">Delete</button>
        </li>
      </ul>
  
      <h2>{{ isEditing ? 'Edit Movie' : 'Add Movie' }}</h2>
      <form @submit.prevent="isEditing ? updateMovie() : addMovie()">
        <label>Title:</label>
        <input type="text" v-model="form.title" required />
        <label>Description:</label>
        <textarea v-model="form.description"></textarea>
        <label>Year:</label>
        <input type="number" v-model="form.year" required />
        <button type="submit">{{ isEditing ? 'Update Movie' : 'Add Movie' }}</button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        movies: [], // Movie list
        form: {
          id: null,
          title: '',
          description: '',
          year: ''
        },
        isEditing: false // Flag to toggle between Add and Update
      };
    },
    methods: {
      // Fetch movies from API
      async fetchMovies() {
        try {
          const response = await axios.get('http://127.0.0.1:8000/api/movies');
          this.movies = response.data;
        } catch (error) {
          console.error('Error fetching movies:', error);
        }
      },
      // Add a new movie
      async addMovie() {
        try {
          const response = await axios.post('http://127.0.0.1:8000/api/movies', this.form);
          this.movies.push(response.data); // Add to the local list
          this.resetForm(); // Clear the form
        } catch (error) {
          console.error('Error adding movie:', error);
        }
      },
      // Populate form for editing
      editMovie(movie) {
        this.isEditing = true;
        this.form = { ...movie }; // Copy movie details to the form
      },
      // Update an existing movie
      async updateMovie() {
        try {
          const response = await axios.put(`http://127.0.0.1:8000/api/movies/${this.form.id}`, this.form);
          const index = this.movies.findIndex(movie => movie.id === this.form.id);
          this.movies[index] = response.data; // Update the local list
          this.resetForm();
          this.isEditing = false; // Reset to Add mode
        } catch (error) {
          console.error('Error updating movie:', error);
        }
      },
      // Delete a movie
      async deleteMovie(id) {
        try {
          await axios.delete(`http://127.0.0.1:8000/api/movies/${id}`);
          this.movies = this.movies.filter(movie => movie.id !== id); // Remove from the local list
        } catch (error) {
          console.error('Error deleting movie:', error);
        }
      },
      // Reset the form
      resetForm() {
        this.form = { id: null, title: '', description: '', year: '' };
      }
    },
    mounted() {
      this.fetchMovies(); // Load movies on component mount
    }
  };
  </script>
  
  <style>
  /* Add basic styling for better visuals */
  body {
    font-family: Arial, sans-serif;
    margin: 20px;
  }
  h1, h2 {
    color: #333;
  }
  form {
    margin-bottom: 20px;
  }
  button {
    margin-left: 10px;
    padding: 5px 10px;
    cursor: pointer;
  }
  </style>
  