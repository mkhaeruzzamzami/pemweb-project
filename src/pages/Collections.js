import React from "react";
import { Container, Row, Col, Card } from "react-bootstrap";

const Collections = () => {
  const famousPaintings = [
    {
      title: "A View of Mount Megamendung",
      image: "/images/Lukisan_Megamendug.png",
    },
    {
      title: "The Ruins and The Piano",
      image: "/images/Lukisan_RuinsPiano.png",
    },
  ];

  const newPaintings = [
    {
      title: "Pasukan Kita di Bawah Pimpinan Panglima Diponegoro",
      image: "/images/Lukisan_wanitaddk.webp",
    },
    {
      title: "The Card Players karya Paul Cezanne (1892)",
      image: "/images/Lukisan_wanitaddk.webp",
    },
  ];

  return (
    <Container className="my-5">
      <div className="d-flex justify-content-center align-items-center">
        <img src="/images/Lukisan_biruabstrak.webp" height="450px" />
      </div>
      {/* Section Lukisan Terkenal */}
      <h2 className="mt-5 mb-4">Lukisan Terkenal</h2>
      <Row className="mb-5">
        {famousPaintings.map((painting, index) => (
          <Col md={6} key={index}>
            <Card className="mb-4">
              <Card.Img variant="top" src={painting.image} />
              <Card.Body>
                <Card.Title>{painting.title}</Card.Title>
              </Card.Body>
            </Card>
          </Col>
        ))}
      </Row>

      {/* Section Lukisan Terbaru */}
      <h2 className="mt-5 mb-4">Lukisan Terbaru</h2>
      <Row className="mb-5">
        {newPaintings.map((painting, index) => (
          <Col md={6} key={index}>
            <Card className="mb-4">
              <Card.Img variant="top" src={painting.image} />
              <Card.Body>
                <Card.Title>{painting.title}</Card.Title>
              </Card.Body>
            </Card>
          </Col>
        ))}
      </Row>

      {/* Quote Cards */}
      <Row className="my-5">
        <Col md={6} className="mb-4">
          <Card
            className="h-100 text-center text-white bg-quote"
            style={{ backgroundImage: `url('/images/Lukisan_wanitaddk.webp')` }}
          >
            <Card.Body className="bg-overlay p-4">
              <blockquote className="blockquote mb-0">
                <p>"Seni adalah ekspresi jiwa."</p>
              </blockquote>
            </Card.Body>
          </Card>
        </Col>
        <Col md={6} className="mb-4">
          <Card
            className="h-100 text-center text-white bg-quote"
            style={{ backgroundImage: `url(/images/Lukisan_wanitaddk.webp)` }}
          >
            <Card.Body className="bg-overlay p-4">
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
