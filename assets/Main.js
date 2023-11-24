import React from "react";
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import Header from './components/Header';
import Footer from './components/Footer';
import User from './pages/User';
import AddUser from './pages/AddUser';
import UserDetails from './pages/UserDetails';
import { StrictMode } from "react";
import { createRoot } from "react-dom/client";

function Main() {

  return (
      <Router>
        <div className="app">
          <Header />
          <div className="footer">
            <Footer />
          </div>
          <div className="Route">
            <Routes>
              <Route path="users" element={<User/>} />
              <Route path="users/add" element={<AddUser/>} />
              <Route path="users/:id" element={<UserDetails/>} />
            </Routes>
          </div>
        </div>
      </Router>
  )
}

export default Main

if (document.getElementById('app')) {
    const rootElement = document.getElementById("app");
    const root = createRoot(rootElement);
 
    root.render(
        <StrictMode>
            <Main />
        </StrictMode>
    );
}
