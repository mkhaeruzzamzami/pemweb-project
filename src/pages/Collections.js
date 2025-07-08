import React, { useEffect, useState } from "react";
import { Container, Row, Col, Card } from "react-bootstrap";
import { Link } from "react-router-dom";
import { getAllLukisan } from "../api/fetch";

const Collections = () => {
  // 🔒 Data hardcoded (lukisan offline)
  const paintings = [
    {
      id: "offline-1",
      title: "A View of Mount Megamendung",
      image: "/images/Lukisan_Pangeran_Diponegoro.jpg",
    },
    {
      id: "offline-2",
      title: "The Ruins and The Piano",
      image: "/images/Lukisan_Pemburuan_Rusa.webp",
    },
    {
      id: "offline-3",
      title: "Pasukan Kita di Bawah Pimpinan Panglima Diponegoro",
      image: "/images/Lukisan_Megamendung.webp",
    },
    {
      id: "offline-4",
      title: "The Card Players karya Paul Cezanne (1892)",
      image: "/images/Lukisan_Theruins_ThePiano.webp",
    },
  ];

  // 🔄 Data dari database
  const [lukisanList, setLukisanList] = useState([]);

  useEffect(() => {
    const fetchData = async () => {
      const data = await getAllLukisan();
      setLukisanList(data);
    };
    fetchData();
  }, []);

  return (
    <Container className="my-5">
      <div className="d-flex justify-content-center align-items-center">
        <img src="/images/Bg_Home.jpeg" alt="background" height="450px" />
      </div>

      <h2 className="mt-5 mb-4">Koleksi Lukisan</h2>
      <Row className="mb-5">
        {/* Render lukisan dari database */}
        {lukisanList.map((painting) => (
          <Col md={6} key={`online-${painting.id}`}>
            <Link
              to={`/gallery/${painting.id}`}
              style={{ textDecoration: "none", color: "inherit" }}
            >
              <Card className="mb-4">
                <Card.Img
                  variant="top"
                  src={painting.gambar_url}
                  alt={painting.judul}
                  style={{ height: "300px", objectFit: "cover" }}
                />
                <Card.Body>
                  <Card.Title>{painting.judul}</Card.Title>
                </Card.Body>
              </Card>
            </Link>
          </Col>
        ))}

        {/* Render lukisan hardcoded */}
        {paintings.map((painting) => (
          <Col md={6} key={painting.id}>
            <Link
              to={`/gallery/${painting.id}`}
              style={{ textDecoration: "none", color: "inherit" }}
            >
              <Card className="mb-4">
                <Card.Img
                  variant="top"
                  src={painting.image}
                  alt={painting.title}
                  style={{ height: "300px", objectFit: "cover" }}
                />
                <Card.Body>
                  <Card.Title>{painting.title}</Card.Title>
                </Card.Body>
              </Card>
            </Link>
          </Col>
        ))}
      </Row>

      {/* Quotes section tetap */}
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
