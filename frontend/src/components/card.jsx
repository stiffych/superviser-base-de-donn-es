import Card from '@mui/material/Card';
import CardContent from '@mui/material/CardContent';
import Typography from '@mui/material/Typography';
import Button from '@mui/material/Button';
import CardActionArea from '@mui/material/CardActionArea';
import CardActions from '@mui/material/CardActions';

export default function MultiActionAreaCard({matricule,nom,salaire,onDelete,onEdit}) {
  return (
    <Card sx={{ maxWidth: 365 }}>
      <CardActionArea>
        <CardContent>
          <Typography gutterBottom variant="h5" component="div">
            {matricule}
          </Typography>
          <Typography variant="body2" sx={{ color: 'text.secondary' }}>
           nom:{nom}
          </Typography>
          <Typography variant="body2" sx={{ color: 'text.dark' }}>
           salaire:{salaire} Ar
          </Typography>
        </CardContent>
      </CardActionArea>
      <CardActions>
        <Button size="small" color="primary" onClick={() => onEdit({ matricule, nom, salaire })}>
          modifier
        </Button>
        <Button size="small" color="error" onClick={() => onDelete(matricule)}>
          supprimer
        </Button>
      </CardActions>
    </Card>
  );
}
