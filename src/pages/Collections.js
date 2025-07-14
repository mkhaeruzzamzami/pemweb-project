import React, { useEffect, useState } from "react";
import { Container, Row, Col, Card } from "react-bootstrap";
import { Link } from "react-router-dom";
import { getAllLukisan } from "../api/fetch";

const Collections = () => {
  const [lukisanList, setLukisanList] = useState([]);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const data = await getAllLukisan();
        // Validasi supaya tidak error .map is not a function
        if (Array.isArray(data)) {
          setLukisanList(data);
        } else {
          console.error("Data lukisan tidak berbentuk array:", data);
          setLukisanList([]);
        }
      } catch (error) {
        console.error("Gagal fetch data lukisan:", error);
        setLukisanList([]);
      }
    };

    fetchData();
  }, [lukisanList]);

  return (
    <Container className="my-5">
      <div className="d-flex justify-content-center align-items-center">
        <img src="/images/Bg_Home.jpeg" alt="background" height="450px" />
      </div>

      <h2 className="mt-5 mb-4">Koleksi Lukisan</h2>
      <Row className="mb-5">
        {lukisanList.length > 0 ? (
          lukisanList.map((painting) => (
            <Col md={6} lg={4} key={painting.id}>
              <Link
                to={`/gallery/${painting.id}`}
                style={{ textDecoration: "none", color: "inherit" }}
              >
                <Card className="mb-4 h-100 shadow">
                  <Card.Img
                    variant="top"
                    src={painting.gambar_url}
                    alt={painting.judul}
                    onError={(e) => {
                      e.target.onerror = null;
                      e.target.src = "/images/fallback.jpg";
                    }}
                    style={{ height: "300px", objectFit: "cover" }}
                  />
                  <Card.Body>
                    <Card.Title>{painting.judul}</Card.Title>
                    <Card.Text>
                      <strong>Tema:</strong> {painting.tema}
                    </Card.Text>
                  </Card.Body>
                </Card>
              </Link>
            </Col>
          ))
        ) : (
          <p>Tidak ada lukisan ditemukan dari database.</p>
        )}
      </Row>

      {/* Quotes Section */}
      <Row className="my-5">
        <Col md={6} className="mb-4">
          <Card
            className="h-100 text-center text-white"
            style={{
              backgroundImage: `url('/images/Lukisan_Ekspresijiwa.jpg')`,
              backgroundSize: "cover",
              backgroundPosition: "center",
            }}
          >
            <Card.Body className="bg-dark bg-opacity-50 p-4">
              <blockquote className="blockquote mb-0">
                <p>"Seni adalah ekspresi jiwa."</p>
              </blockquote>
            </Card.Body>
          </Card>
        </Col>
        <Col md={6} className="mb-4">
          <Card
            className="h-100 text-center text-white"
            style={{
              backgroundImage: `url('/images/Lukisan_Warnaberbicara.jpg')`,
              backgroundSize: "cover",
              backgroundPosition: "center",
            }}
          >
            <Card.Body className="bg-dark bg-opacity-50 p-4">
              <blockquote className="blockquote mb-0">
                <p>"Warna berbicara tanpa suara!"</p>
              </blockquote>
            </Card.Body>
          </Card>
        </Col>
      </Row>
    </Container>
  );
};

export default Collections;
