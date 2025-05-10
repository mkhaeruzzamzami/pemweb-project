import React from "react";
import { Container, Row, Col, Form, Button } from "react-bootstrap";

const Footer = () => {
  return (
    <footer className="bg-black text-white py-5 mt-5">
      <Container>
        <Row className="justify-content-center">
          <Col md={8} className="text-center">
            <h2 className="text-pink mb-4">Pincela.</h2>

            <Row className="mb-4">
              <Col md={3} className="mb-3 mb-md-0">
                <p className="mb-1">Menu</p>
                <p className="mb-1">Collections</p>
              </Col>
              <Col md={3} className="mb-3 mb-md-0">
                <p className="mb-1">Contact</p>
                <p className="mb-1">About</p>
              </Col>
              <Col md={6}>
                <Form className="d-flex flex-column align-items-center">
                  <Form.Control
                    type="email"
                    placeholder="Input your email"
                    className="mb-3 bg-dark text-white border-dark"
                    style={{ width: "100%", maxWidth: "300px" }}
                  />
                  <Button
                    variant="pink"
                    className="px-4"
                    style={{
                      backgroundColor: "#ff69b4",
                      borderColor: "#ff69b4",
                      color: "white",
                    }}
                  >
                    Subscribe
                  </Button>
                </Form>
              </Col>
            </Row>

            <p className="mb-2">© 2025 Pincela.</p>
          </Col>
        </Row>
      </Container>
    </footer>
  );
};

export default Footer;
