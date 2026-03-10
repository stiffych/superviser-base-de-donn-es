import React, { useState } from "react";
import { Container, TextField, Button, Typography, Box, Alert } from "@mui/material";
import axios from "axios";

export default function Login({ onLoginSuccess }) {
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");

  const handleLogin = () => {
    if (!username || !password) {
      setError("Veuillez remplir tous les champs.");
      return;
    }
    axios
       .post("http://localhost:8000/index.php/user", { username, password },{withCredentials: true})
        .then((res) => {
    console.log("reponse",res.data);
    if (res.data.success) {
      onLoginSuccess(res.data.user); 
    } else {
      setError(res.data.message || "username ou mot de passe incorrect");
    }
      })
      .catch(() => setError("Erreur de connexion au serveur."));
  };

  return (
    <Container>
      <Box sx={{ mt: 8, p: 4, boxShadow: 3, borderRadius: 2 }}>
        <Typography variant="h5" gutterBottom>
          Connexion
        </Typography>

        {error && <Alert severity="error" sx={{ mb: 2 }}>{error}</Alert>}

        <TextField
          label="surnom"
          type="text"
          fullWidth
          value={username}
          onChange={(e) => setUsername(e.target.value)}
          sx={{ mb: 2 }}
        />
        <TextField
          label="Mot de passe"
          type="password"
          fullWidth
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          sx={{ mb: 3 }}
        />

        <Button variant="contained" fullWidth onClick={handleLogin}>
          Se connecter
        </Button>
      </Box>
    </Container>
  );
}