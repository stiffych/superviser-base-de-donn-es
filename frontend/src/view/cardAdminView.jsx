import { styled } from '@mui/material/styles';
import Table from '@mui/material/Table';
import TableBody from '@mui/material/TableBody';
import TableCell, { tableCellClasses } from '@mui/material/TableCell';
import TableContainer from '@mui/material/TableContainer';
import TableHead from '@mui/material/TableHead';
import TableRow from '@mui/material/TableRow';
import Paper from '@mui/material/Paper';
import { Box,Typography,Grid,Card,CardContent } from '@mui/material';
import { useEffect, useState } from 'react';
import axios from 'axios';

const StyledTableCell = styled(TableCell)(({ theme }) => ({
  [`&.${tableCellClasses.head}`]: {
    backgroundColor: theme.palette.common.black,
    color: theme.palette.common.white,
  },
  [`&.${tableCellClasses.body}`]: {
    fontSize: 14,
  },
}));

const StyledTableRow = styled(TableRow)(({ theme }) => ({
  '&:nth-of-type(odd)': {
    backgroundColor: theme.palette.action.hover,
  },
  // hide last border
  '&:last-child td, &:last-child th': {
    border: 0,
  },
}));

export default function CardAdminView() {
  const [audit ,setAudit] = useState({logs: [], stats: { total_insert: 0, total_update: 0, total_delete: 0 } });
  useEffect(() =>{
    axios
    .get("http://localhost:8000/index.php/audit", { withCredentials: true })
    .then((res) =>setAudit(res.data))
    .catch((err)=>console.error(err));
  },[])
 const getTotal = (type) => {
  if (audit.stats && Array.isArray(audit.stats)) {
    const found = audit.stats.find(s => s.operation_type === type);
    return found ? found.total : 0;
  }
  return 0;
};
  return (
    <Box sx={{ mt: 3 }}>
      <Typography variant="h5" mb={2}>Journal d'Audit</Typography>
    <TableContainer component={Paper}>
      <Table sx={{ minWidth: 700 }} aria-label="customized table">
        <TableHead>
          <TableRow>
            <StyledTableCell align="right">type_action</StyledTableCell>
            <StyledTableCell align="right">date </StyledTableCell>
            <StyledTableCell align="right">matricule</StyledTableCell>
            <StyledTableCell align="right">nom</StyledTableCell>
             <StyledTableCell align="right">salaire_ancien</StyledTableCell>
            <StyledTableCell align="right">salaire_nouv</StyledTableCell>
             <StyledTableCell align="right">user</StyledTableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          {audit.logs.map((row,index) => (
            <StyledTableRow key={index}>
               <StyledTableCell component="th" scope='row'><b>{row.operation_type}</b></StyledTableCell>
             <StyledTableCell align="right">{row.date_action}</StyledTableCell>
              <StyledTableCell align="right">{row.id_modif}</StyledTableCell>
              <StyledTableCell align="right">{row.nom_employe}</StyledTableCell>
              <StyledTableCell align="right">{row.old_value && row.old_value !== "null" ? `${row.old_value} Ar` : '-'}</StyledTableCell>
              <StyledTableCell align="right">{row.new_value ? `${row.new_value} Ar` : '-'}</StyledTableCell>
              <StyledTableCell align="right">{row.username}</StyledTableCell>
            </StyledTableRow>
          ))}
        </TableBody>
      </Table>
    </TableContainer>
    <Box sx={{ mt: 4, mb: 4 }}>
        <Typography variant="h6" gutterBottom>Statistiques des opérations</Typography>
        <Grid container spacing={3}>
          <Grid item xs={12} sm={4}>
            <Card sx={{ bgcolor: '#e8f5e9' }}>
              <CardContent>
                <Typography color="textSecondary">Insertions</Typography>
                <Typography variant="h4">{getTotal('ajout') || 0}</Typography>
              </CardContent>
            </Card>
          </Grid>
          <Grid item xs={12} sm={4}>
            <Card sx={{ bgcolor: '#e3f2fd' }}>
              <CardContent>
                <Typography color="textSecondary">Modifications</Typography>
                <Typography variant="h4">{getTotal('modification') || 0}</Typography>
              </CardContent>
            </Card>
          </Grid>
          <Grid item xs={12} sm={4}>
            <Card sx={{ bgcolor: '#ffebee' }}>
              <CardContent>
                <Typography color="textSecondary">Suppressions</Typography>
                <Typography variant="h4">{getTotal('suppression') || 0}</Typography>
              </CardContent>
            </Card>
          </Grid>
        </Grid>
      </Box>
    </Box>
  );
}
