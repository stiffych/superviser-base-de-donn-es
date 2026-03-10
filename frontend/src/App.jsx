import React, { useState } from "react";
import { Container, Typography,Button } from "@mui/material";
import Login from "./view/loginView";
import CardListView from "./view/cardView";
import CardAdminView from "./view/cardAdminView";
import AppBar from "@mui/material/AppBar";
import Toolbar from "@mui/material/Toolbar";
import axios from "axios";
axios.defaults.withCredentials = true;

function App() {
  const [user, setUser] = useState(null);

  const handleLoginSuccess = (userData) => {
    console.log("utilisateur connecté" ,userData);
    setUser(userData);
  };
  const handleLogout = () => {
    setUser(null);
  };

  return (
    <Container sx={{position:'relative'}}>
      {!user ? (
        <Login onLoginSuccess={handleLoginSuccess} />
      ) : (
        <>
        <AppBar position="fixed" sx={{backgroundColor:'white', color:'black'}}>
          <Toolbar>
          <Typography variant="h5" sx={{position:'relative', paddingTop:'0.25cm'}}>
            {/* Bonjour, {user.username} ! */}
            GESTION DES EMPLOYÉS

            <Button onClick={handleLogout} variant="outlined" sx={{marginLeft:'19cm', mb: 0}}>
            Déconnexion
          </Button>
          </Typography>
          </Toolbar>
          </AppBar>
          
          <Toolbar />

          {user.role === "admin" ? (
            <CardAdminView user={user} /> 
          ) : (
            <CardListView user={user} /> 
          )}
        </>
      )}
    </Container>
  );
}

export default App;