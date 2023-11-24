import React from 'react';
import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import Table from 'react-bootstrap/Table';
import "./User.css"

const User = () => {
  const [users, setUsers] = useState([]);

  useEffect(() => {
    // Fetch user data from your API endpoint
    fetch('http://127.0.0.1:8000/api/users')
      .then((response) => response.json())
      .then((data) => setUsers(data))
      .catch((error) => console.error('Error fetching user data:', error));
  }, []);


  const handleDelete = (userId) => {
    // URL de l'API de suppression
    fetch(`http://127.0.0.1:8000/api/users/${userId}`, {
      method: 'DELETE',
    })
      .then(() => {
        setUsers(users.filter((user) => user.id !== userId));
      })
      .catch((error) =>
        console.error("Erreur lors de la suppression de l'utilisateur", error)
      );
  };

  return (
    <div className='container'>
      <h1 className='mes_users'><strong>Mes utilisateurs</strong></h1>
      <Table className="table table-striped">
        <thead>
          <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Adresse</th>
            <th>Telephone</th>
            <th>Date de naissance</th>
            <th>Age</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          {users.map((user) => (
            <tr key={user.id}>
              <td>
                <Link to={`/users/${user.id}`} className="details">
                  {user.nom}{' '}
                </Link>
              </td>
              <td>{user.prenom}</td>
              <td>{user.email}</td>
              <td>{user.adresse}</td>
              <td>{user.tel}</td>
              <td>{new Intl.DateTimeFormat('fr-FR').format(new Date(user.bithDate))}</td>
              <td>{user.age}</td>
              <td>
                <button className="btn btn-danger" onClick={() => handleDelete(user.id)}>Supprimer
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </Table>
    </div>
  );
};

export default User;
