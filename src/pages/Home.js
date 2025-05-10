// import React from "react";
// import { Container, Row, Col, Card } from "react-bootstrap";
// import "./Home.css"; // Kita akan buat file CSS khusus

// const Home = () => {
//   const paintings = [
//     {
//       title: "Penangkapan Pangeran Diponegoro",
//       image: "/images/Lukisan_Pangeran_Diponegoro.jpg",
//     },
//     {
//       title: "Kawan – Kawan Revolusi",
//       image: "../images/Lukisan_Kawan_Revolusi.jpg",
//     },
//     {
//       title: "Nyi Roro Kidul",
//       image: "/images/Lukisan_Nyirorokidul.jpeg",
//     },
//     {
//       title: "Potret Diri dan Pipanya",
//       image: "/images/diridanpipanya.jpg",
//     },
//     {
//       title: "Pemburuan Rusa",
//       image: "/images/Lukisan_Pemburuan_Rusa.jpg",
//     },
//   ];

//   return (
//     <div className="home-page">
//       {/* Hero Section dengan Background */}
//       <div className="hero-section">
//         <Container>
//           <h1 className="hero-title">Pincela.</h1>
//           <p className="hero-subtitle">
//             Pincela adalah web pameran lukisan digital yang menampilkan karya
//             seni dari berbagai seniman dalam tampilan galeri yang elegan dan
//             sangat menarik
//           </p>
//         </Container>
//       </div>

//       {/* Gallery Section */}
//       <Container className="gallery-container">
//         <Row className="gallery-row">
//           {paintings.map((painting, index) => (
//             <Col key={index} xs={12} md={6} lg={4} className="mb-4">
//               <Card className="painting-card">
//                 <Card.Img
//                   variant="top"
//                   src={painting.image}
//                   alt={painting.title}
//                 />
//                 <Card.Body>
//                   <Card.Title>{painting.title}</Card.Title>
//                 </Card.Body>
//               </Card>
//             </Col>
//           ))}
//         </Row>
//       </Container>
//     </div>
//   );
// };

// export default Home;
import React, { useEffect } from "react";
import { Container, Row, Col, Card } from "react-bootstrap";
import AOS from "aos";
import "aos/dist/aos.css";
import "./Home.css";

const Home = () => {
  useEffect(() => {
    AOS.init({ duration: 1000 }); // durasi animasi
  }, []);

  const paintings = [
    {
      title: "Penangkapan Pangeran Diponegoro",
      image: "/images/Lukisan_Pangeran_Diponegoro.jpg",
    },
    {
      title: "Kawan – Kawan Revolusi",
      image: "../images/Lukisan_Kawan_Revolusi.jpg",
    },
    {
      title: "Nyi Roro Kidul",
      image: "/images/Lukisan_Nyirorokidul.jpeg",
    },
    {
      title: "Potret Diri dan Pipanya",
      image: "/images/diridanpipanya.jpg",
    },
    {
      title: "Pemburuan Rusa",
      image: "/images/Lukisan_Pemburuan_Rusa.jpg",
    },
  ];

  return (
    <div className="home-page">
      {/* Hero Section */}
      <div className="hero-section">
        <Container>
          <h1 className="hero-title">Pincela.</h1>
          <p className="hero-subtitle">
            Pincela adalah web pameran lukisan digital yang menampilkan karya
            seni dari berbagai seniman dalam tampilan galeri yang elegan dan
            sangat menarik
          </p>
        </Container>
      </div>

      {/* Gallery Section */}
      <Container className="gallery-container">
        <Row className="gallery-row">
          {paintings.map((painting, index) => (
            <Col
              key={index}
              xs={12}
              md={6}
              lg={4}
              className="mb-4"
              data-aos="fade-up"
            >
              <Card className="painting-card">
                <Card.Img
                  variant="top"
                  src={painting.image}
                  alt={painting.title}
                />
                <Card.Body>
                  <Card.Title>{painting.title}</Card.Title>
                </Card.Body>
              </Card>
            </Col>
          ))}
        </Row>
      </Container>
    </div>
  );
};

export default Home;
