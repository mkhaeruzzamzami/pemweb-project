import React from "react";
import { Navbar, Nav, Container, Button } from "react-bootstrap";
import { Link, useLocation, useNavigate } from "react-router-dom";

const CustomNavbar = () => {
  const location = useLocation();
  const navigate = useNavigate();

  // Path di mana tombol "Kembali" ditampilkan (misalnya saat buka /gallery/:id)
  const isDetailPage = location.pathname.startsWith("/gallery/");

  return (
    <Navbar bg="light" expand="lg" className="py-3">
      <Container>
        <Navbar.Brand as={Link} to="/" className="fw-bold">
          Pincela.
        </Navbar.Brand>

        {/* Tombol Kembali ditampilkan hanya di halaman detail */}
        {isDetailPage && (
          <Button variant="outline-secondary" onClick={() => navigate(-1)} className="ms-3">
            ← Kembali
          </Button>
        )}

        <Navbar.Toggle aria-controls="basic-navbar-nav" />
        <Navbar.Collapse id="basic-navbar-nav">
          <Nav className="ms-auto">
            <Nav.Link as={Link} to="/">
              Home
            </Nav.Link>
            <Nav.Link as={Link} to="/support">
              Add
            </Nav.Link>
            <Nav.Link as={Link} to="/collections">
              Collections
            </Nav.Link>
            <Nav.Link as={Link} to="/about">
              About
            </Nav.Link>
            <Nav.Link as={Link} to="/contact">
              Contact
            </Nav.Link>
          </Nav>
        </Navbar.Collapse>
      </Container>
    </Navbar>
  );
};

export default CustomNavbar;
