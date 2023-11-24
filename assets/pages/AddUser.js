import React from "react";
import { useState } from "react";
import { useNavigate } from "react-router-dom";

function AddUser() {
    const [nom, setNom] = useState('');
    const [prenom, setPrenom] = useState('');
    const [email, setEmail] = useState('');
    const [adresse, setAdresse] = useState('');
    const [tel, setTel] = useState('');
    const [bithDate, setBithDate] = useState('');
    const [possessionNom, setPossessionNom] = useState('');
    const [possessionValeur, setPossessionValeur] = useState('');
    const [possessionType, setPossessionType] = useState('');
  let navigate = useNavigate();

  const formSubmit = (e) => {
    e.preventDefault();
    console.log("valeur avant envoi:", possessionValeur);
    fetch(`http://127.0.0.1:8000/api/users`, {
      method: "POST",
        headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        nom: nom,
        prenom: prenom,
        email: email,
        adresse: adresse,
        tel: tel,
        bithDate: new Date(bithDate).toISOString().split('T')[0],
        possession: {
          nom: possessionNom,
          valeur: parseFloat(possessionValeur),
          type: possessionType,
        },
            })
      })

      .then((res) => res.json())
      .then((data) => {
        console.log(data);
  navigate("/users");

      });
  };

  return (
    <>
      <h2 className="titreadd">Ajouter un nouveau utilisateur </h2>

      <form className="container mt-3" onSubmit={formSubmit}>
      <div className="row">
          <div className="col-md-6">
            <div className="form-group mb-3">
              <label className="mb-1" htmlFor="nom">
                Nom
              </label>
              <input
                type="text"
                className="form-control"
                id="nom"
                aria-describedby="nom"
                placeholder="Votre nom"
                onChange={(e) => {
                  setNom(e.target.value);
                }}
              />
            </div>
          </div>

          <div className="col-md-6">
            <div className="form-group mb-3">
              <label className="mb-1" htmlFor="prenom">
                Prenom
              </label>
              <input
                type="text"
                className="form-control"
                id="prenom"
                aria-describedby="prenom"
                placeholder="Votre prenom"
                onChange={(e) => {
                  setPrenom(e.target.value);
                }}
              />
            </div>
          </div>
        </div>

        <div className="form-group mb-3">
          <label className="mb-1" htmlFor="email">Email</label>
          <input
            type="Email"
            className="form-control"
            id="email"
            aria-describedby="email"
            placeholder="Votre email"
            onChange={(e) => {
              setEmail(e.target.value);
            }}
          />
        </div>

        <div className="form-group mb-3">
          <label className="mb-1" htmlFor="adresse">Adresse</label>
          <input
            type="text"
            className="form-control"
            id="adresse"
            aria-describedby="adresse"
            placeholder="Votre adresse"
            onChange={(e) => {
              setAdresse(e.target.value);
            }}
          />
        </div>

        <div className="form-group mb-3">
          <label className="mb-1" htmlFor="tel">Numero de télephone</label>
          <input
            type="text"
            className="form-control"
            id="tel"
            aria-describedby="tel"
            placeholder="Votre numéro de télephone"
            onChange={(e) => {
              setTel(e.target.value);
            }}
          />
        </div>

                
        <div className="form-group mb-3">
          <label className="mb-1" htmlFor="birthdate">Date de naissance</label>
          <input
            type="date"
            className="form-control"
            id="birthdate"
            aria-describedby="birthdate"
            placeholder="Votre date de naissance"
            onChange={(e) => {
              setBithDate(e.target.value);
            }}
          />
        </div>

        <div className="row">
          {/* Nom de possession */}
          <div className="col-md-4">
            <div className="form-group mb-3">
              <label className="mb-1" htmlFor="nomposs">
                Nom de possession
              </label>
              <input
                type="text"
                className="form-control"
                id="nomposs"
                aria-describedby="nomposs"
                placeholder="Veuillez ajouter le nom de votre possession"
                onChange={(e) => {
                  setPossessionNom(e.target.value);
                }}
              />
            </div>
          </div>

          {/* Valeur de possession */}
          <div className="col-md-4">
            <div className="form-group mb-3">
              <label className="mb-1" htmlFor="valeur">
                Valeur de possession
              </label>
              <input
                type="float"
                className="form-control"
                id="valeur"
                aria-describedby="valeur"
                placeholder="Veuillez ajouter la valeur de votre possession"
                onChange={(e) => {
                  setPossessionValeur(e.target.value);
                }}
              />
            </div>
          </div>

          {/* Type de possession */}
          <div className="col-md-4">
            <div className="form-group mb-3">
              <label className="mb-1" htmlFor="typeposs">
                Type de possession
              </label>
              <input
                type="text"
                className="form-control"
                id="typeposs"
                aria-describedby="typeposs"
                placeholder="Veuillez ajouter le type de votre possession"
                onChange={(e) => {
                  setPossessionType(e.target.value);
                }}
              />
            </div>
          </div>
        </div>
        <div className="text-center">
        <button type="submit" className="btn mt-3 ajouter">
          <strong>Ajouter</strong>
        </button>
        </div>

      </form>
    </>
  );
}
export default AddUser;