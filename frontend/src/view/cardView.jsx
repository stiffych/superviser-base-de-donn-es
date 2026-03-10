import React, { useEffect, useState } from "react";
import { Button, Modal, Box, TextField, Typography,Grid } from "@mui/material";
import MultiActionAreaCard from "../components/card";
import axios from "axios";

const modalStyle = {
  position: "absolute",
  top: "50%",
  left: "50%",
  transform: "translate(-50%, -50%)",
  width: 400,
  bgcolor: "background.paper",
  boxShadow: 24,
  p: 4,
};

export default function CardListView({user}) {
  const [cards, setCards] = useState([]);

  const [open, setOpen] = useState(false);
  const [isEdit, setIsEdit]= useState(false);
  const [newCard, setNewCard] = useState({ matricule: "", nom: "", salaire: "" });

  useEffect(() =>{
    axios
    .get("http://localhost:8000/index.php/employe")
    .then((res) =>setCards(res.data))
    .catch((err)=>console.error(err));
  },[])

  const handleOpen = () => setOpen(true);
  const handleClose = () => setOpen(false);
  const handleEdit = (cards)=>{
    setNewCard(cards);
    setIsEdit(true);
    setOpen(true);
  };
  

  const handleChange = (e) => {
    setNewCard({ ...newCard, [e.target.name]: e.target.value });
  };

  const handleAddCard = () => {
     if (newCard.matricule && newCard.nom && newCard.salaire) {
      axios
        .post("http://localhost:8000/index.php/employe", {...newCard,username:user.username})
        .then((res) => {
          const addedCard = res.data.matricule ? res.data : newCard;
          setCards([...cards, addedCard]);
          setNewCard({ matricule: "", nom: "", salaire: "" });
          handleClose();
        })
        .catch((err) => console.error(err));
    }
  };
  const handleModifier = ()=>{
    axios
    .put("http://localhost:8000/index.php/employe", {...newCard,username:user.username})
    .then((res)=>{
      console.log(res.data);
      if(res.data.success){
        setCards(prev =>
          prev.map(card => card.matricule === newCard.matricule ? newCard : card)
        );
        handleClose();
        setIsEdit(false);
        setNewCard({matricule: "", nom: "", salaire: ""});
      }
    })
    .catch(err => console.error(err));
  }
  const handleDelete = (matricule) => {
  if (window.confirm("Voulez-vous vraiment supprimer cet employé ?")) {
    axios
      .delete(`http://localhost:8000/index.php/employe/${matricule}`,{data:{username:user.username}})
      .then((res) => {
        console.log(res.data);
       if(res.data.success){
        setCards(cards.filter((card) => card.matricule !== matricule));
        console.log("Supprimé de la base et de l'affichage");
       }else {
        alert("Erreur serveur : " + res.data.message);
        }
      })
      .catch((err) => console.error("Erreur suppression:", err));
  }
};

  return (
    <Box sx={{mb: 34}}>
      {/* Bouton Ajouter */}
      <Button variant="contained" onClick={handleOpen} sx={{ mb: 2 }}>
        Ajouter
      </Button>
      <Grid container spacing={3}>

       {cards.map((item) => (
  <MultiActionAreaCard 
  item md={4} xs={12} sm={6}
    key={item.matricule} 
    matricule={item.matricule}
    nom={item.nom}
    salaire={item.salaire}
    onDelete={handleDelete}
    onEdit={handleEdit}
  />
))}
</Grid>

      {/* Modal */}
      <Modal open={open} onClose={handleClose}>
        <Box sx={modalStyle}>
          <Typography variant="h6" mb={2}>
            Ajouter un Card
          </Typography>
          <TextField
            fullWidth
            label="matricule"
            name="matricule"
            value={newCard.matricule}
            onChange={handleChange}
            sx={{ mb: 2 }}
          />
          <TextField
            fullWidth
            label="nom"
            name="nom"
            value={newCard.nom}
            onChange={handleChange}
            sx={{ mb: 2 }}
          />
           <TextField
            fullWidth
            label="salaire"
            name="salaire"
            value={newCard.salaire}
            onChange={handleChange}
            sx={{ mb: 2 }}
          />
          <Button variant="contained" onClick={isEdit ? handleModifier : handleAddCard}>
            {isEdit ? "Modifier" : "Ajouter"}
          </Button>
        </Box>
      </Modal>
     
    </Box>
  );
}