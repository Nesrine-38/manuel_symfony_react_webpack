import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';

const UserDetails = () => {
  const { id } = useParams();
  const [user, setUser] = useState({});
  const [possessions, setPossessions] = useState([]);

  useEffect(() => {
    const fetchUserDetails = async () => {
      try {
        // Récupérer les détails de l'utilisateur
        const userResponse = await fetch(`http://127.0.0.1:8000/api/users/${id}`);
        const userData = await userResponse.json();
        setUser(userData);

        // Récupérer les possessions de l'utilisateur
        const possessionsResponse = await fetch(`http://127.0.0.1:8000/api/users/${id}/possessions`);
        const possessionsData = await possessionsResponse.json();
        setPossessions(possessionsData);
      } catch (error) {
        console.error('Error fetching user details:', error);
      }
    };

    fetchUserDetails();
  }, [id]);

  return (
    <div>
      <h2 className='userdetails'>Les détails de l'utilisateur</h2>
      <table className="table">
        <tbody>
          <tr>
            <th>Nom</th>
            <td>{user.nom}</td>
          </tr>
          <tr>
            <th>Prenom</th>
            <td>{user.prenom}</td>
          </tr>
          <tr>
            <th>Email</th>
            <td>{user.email}</td>
          </tr>
          <tr>
            <th>Adresse</th>
            <td>{user.adresse}</td>
          </tr>
          <tr>
            <th>Tel</th>
            <td>{user.tel}</td>
          </tr>
          <tr>
            <th>Date de naissance</th>
            <td>{user.bithDate}</td>
          </tr>
          <tr>
            <th>Age</th>
            <td>{user.age}</td>
          </tr>
        </tbody>
      </table>

      <h3 className='possession'>Les Possessions de cet utilisateur </h3>
      <table className="table">
        <thead>
          <tr>
            <th>Nom</th>
            <th>Valeur</th>
            <th>Type</th>
          </tr>
        </thead>
        <tbody>
          {possessions.map((possession) => (
            <tr key={possession.id}>
              <td>{possession.nom}</td>
              <td>{possession.valeur}</td>
              <td>{possession.type}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};


export default UserDetails;
