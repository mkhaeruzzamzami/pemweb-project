import React, { useState } from "react";
import { Container, Row, Col, Form, Button } from "react-bootstrap";
import contactImage from "../images/contact.jpg"; // Ganti sesuai path gambar
// import img3 from "../images/Lukisan_wanitaddk.webp";

const Contact = () => {
  const [formData, setFormData] = useState({
    nama: "",
    email: "",
    pesan: "",
  });

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prevData) => ({
      ...prevData,
      [name]: value,
    }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    // Di sini Anda bisa mengirim data ke backend atau melakukan sesuatu dengan formData
    console.log("Form submitted:", formData);
  };

  return (
    <div
      className="contact-section"
      style={{ backgroundColor: "#f2f0ff", padding: "60px 0" }}
    >
      <Container>
        {/* Judul & Info Kontak */}
        <div className="text-center mb-5">
          <h2 className="fw-bold mb-3">Contact us</h2>
          <Row className="justify-content-center text-secondary">
            <Col md={3}>
              <p>📍 Cirebon</p>
            </Col>
            <Col md={3}>
              <p>✉️ team@pincela.com</p>
            </Col>
            <Col md={3}>
              <p>📞 (0261) 123 2024</p>
            </Col>
          </Row>
        </div>

        {/* Form & Gambar */}
        <Row className="bg-white p-5 shadow rounded">
          <Col md={6}>
            <h4 className="mb-4 fw-bold">Contact us</h4>
            <Form onSubmit={handleSubmit}>
              <Form.Group className="mb-3" controlId="formName">
                <Form.Label>Nama</Form.Label>
                <Form.Control
                  type="text"
                  placeholder="Masukkan nama anda"
                  name="nama"
                  value={formData.nama}
                  onChange={handleChange}
                />
              </Form.Group>

              <Form.Group className="mb-3" controlId="formEmail">
                <Form.Label>Email</Form.Label>
                <Form.Control
                  type="email"
                  placeholder="Masukkan email anda"
                  name="email"
                  value={formData.email}
                  onChange={handleChange}
                />
              </Form.Group>

              <Form.Group className="mb-4" controlId="formMessage">
                <Form.Label>Pesan</Form.Label>
                <Form.Control
                  as="textarea"
                  rows={3}
                  placeholder="Tulis pesan anda"
                  name="pesan"
                  value={formData.pesan}
                  onChange={handleChange}
                />
              </Form.Group>

              <Button variant="primary" type="submit">
                Kirim
              </Button>
            </Form>
          </Col>

          {/* Gambar */}
          <Col
            md={6}
            className="d-flex align-items-center justify-content-center"
          >
            <img
              src={contactImage}
              alt="Map"
              className="img-fluid rounded"
              style={{ maxHeight: "300px", objectFit: "cover" }}
            />
          </Col>
        </Row>
      </Container>
    </div>
  );
};

export default Contact;
