import React from 'react';
import Nav from 'react-bootstrap/Nav';
import { Link } from 'react-router-dom';
import "./Header.css"
const Header = () => {
  return (
      <div className="header-container">
        <div className="header-title">
          <h3><strong>Mon Application</strong></h3>
        </div>
        <Nav className="justify-content-end" activeKey="/users">
          <Nav.Item>
            <Nav.Link className="users" as={Link} to="users">
              <strong>Users</strong>
            </Nav.Link>
          </Nav.Item>
          <Nav.Item>
            <Nav.Link className="addusers" as={Link} to="users/add">
              <strong>Add User</strong>
            </Nav.Link>
          </Nav.Item>
        </Nav>
      </div>
    );
  }

export default Header;
