import React from "react";
import { Container, Row, Col, Card } from "react-bootstrap";
import { FaWhatsapp, FaEnvelope, FaMapMarkerAlt } from "react-icons/fa";
import contactImage from "../images/contact.jpg";

const Contact = () => {
  return (
    <div
      className="contact-section"
      style={{ backgroundColor: "#f2f0ff", padding: "60px 0" }}
    >
      <Container>
        <Row className="bg-white p-5 shadow rounded align-items-center">
          {/* Info Kontak */}
          <Col md={20}>
            <h4 className="mb-4 fw-bold">Hubungi Kami</h4>

            <Card className="mb-3 border-0">
              <Card.Body className="d-flex align-items-center">
                <FaWhatsapp className="me-3 text-success fs-4" />
                <div>
                  <strong>WhatsApp:</strong><br />
                  <a
                    href="https://wa.me/6285712983804"
                    target="_blank"
                    rel="noopener noreferrer"
                  >
                    +62 812-3456-7890
                  </a>
                </div>
              </Card.Body>
            </Card>

            <Card className="mb-3 border-0">
              <Card.Body className="d-flex align-items-center">
                <FaEnvelope className="me-3 text-danger fs-4" />
                <div>
                  <strong>Email:</strong><br />
                  <a href="mailto:aaaaa@gmail.com">pincela@gmail.com</a>
                </div>
              </Card.Body>
            </Card>

            <Card className="mb-3 border-0">
              <Card.Body className="d-flex align-items-center">
                <FaMapMarkerAlt className="me-3 text-primary fs-4" />
                <div>
                  <strong>Alamat:</strong><br />
                  STIKOM Poltek Cirebon
                </div>
              </Card.Body>
            </Card>
          </Col>

          {/* Gambar */}
          <Col md={15} className="text-center">
          <img
            src={contactImage}
            alt="Ilustrasi kontak"
            className="img-fluid rounded"
            style={{
              width: "100%",
              maxWidth: "500px",
              height: "auto",
              objectFit: "cover"
            }}
          />

          </Col>
        </Row>
      </Container>
    </div>
  );
};

export default Contact;
