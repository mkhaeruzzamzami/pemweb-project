import React from "react";
import { Container, Form, Button, Row, Col, Image } from "react-bootstrap";
import supportImage from "../images/Lukisan_coretabstrak.png"; // Sesuaikan path gambar Anda
import "./Support.css";

const Support = () => {
  return (
    <Container className="support-page">
      {/* Header Section with Image Below */}
      <Row className="header-section mb-5">
        <Col className="text-center">
          <h1 className="mb-3">Butuh Bantuan?</h1>
          <p className="lead mb-4">
            Isi formulir berikut untuk menghubungi kami
          </p>
        </Col>
      </Row>

      {/* Form with Side Image (Image on Left, Form on Right) */}
      <Row className="form-section">
        {/* Image Column */}
        <Col lg={5} className="d-none d-lg-block">
          <div className="side-image-container">
            <Image
              src={supportImage}
              alt="Ilustrasi Customer Support"
              className="side-image"
              fluid
            />
            <div className="image-caption mt-3"></div>
          </div>
        </Col>

        {/* Form Column */}
        <Col lg={7} className="ps-lg-5">
          <Form>
            {/* Nama Lengkap */}
            <div className="mb-4">
              <h3 className="section-title mb-3">Nama Lengkap</h3>
              <Row>
                <Col md={6}>
                  <Form.Group className="mb-3">
                    <Form.Label>Nama Depan</Form.Label>
                    <Form.Control
                      type="text"
                      placeholder="Masukkan nama depan"
                    />
                  </Form.Group>
                </Col>
                <Col md={6}>
                  <Form.Group className="mb-3">
                    <Form.Label>Nama Belakang</Form.Label>
                    <Form.Control
                      type="text"
                      placeholder="Masukkan nama belakang"
                    />
                  </Form.Group>
                </Col>
              </Row>
            </div>

            {/* Informasi Kontak */}
            <div className="mb-4">
              <h3 className="section-title mb-3">Informasi Kontak</h3>
              <Form.Group className="mb-3">
                <Form.Label>Email</Form.Label>
                <Form.Control
                  type="email"
                  placeholder="Masukkan alamat email"
                />
              </Form.Group>
              <Form.Group className="mb-3">
                <Form.Label>Nama Perusahaan</Form.Label>
                <Form.Control
                  type="text"
                  placeholder="Masukkan nama perusahaan"
                />
              </Form.Group>
              <Form.Group className="mb-3">
                <Form.Label>Ukuran Perusahaan</Form.Label>
                <Form.Select>
                  <option>Silakan pilih</option>
                  <option>1-10 karyawan</option>
                  <option>11-50 karyawan</option>
                  <option>51-200 karyawan</option>
                  <option>201+ karyawan</option>
                </Form.Select>
              </Form.Group>
            </div>

            {/* Topik Bantuan */}
            <div className="mb-4">
              <h3 className="section-title mb-3">Topik Bantuan</h3>
              <Form.Group className="mb-3">
                <Form.Label>Topik yang sesuai dengan kebutuhan Anda</Form.Label>
                <Form.Select>
                  <option>Silakan pilih</option>
                  <option>Pertanyaan umum</option>
                  <option>Masalah teknis</option>
                  <option>Pertanyaan pembayaran</option>
                  <option>Lainnya</option>
                </Form.Select>
              </Form.Group>
            </div>

            {/* Detail Permintaan */}
            <div className="mb-4">
              <h3 className="section-title mb-3">Detail Permintaan</h3>
              <Form.Group className="mb-3">
                <Form.Label>Bagaimana kami bisa membantu?</Form.Label>
                <Form.Control
                  as="textarea"
                  rows={4}
                  placeholder="Jelaskan kebutuhan Anda secara detail"
                />
              </Form.Group>
            </div>

            <div className="text-center mt-5">
              <Button variant="primary" size="lg" type="submit">
                Kirim Permintaan
              </Button>
            </div>
          </Form>
        </Col>
      </Row>
    </Container>
  );
};

export default Support;
