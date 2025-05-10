import React from "react";
import { Container, Row, Col, Image } from "react-bootstrap";
import backgroundImage from "../images/Lukisan_bg2About.jpeg"; // Gambar latar belakang
import img1 from "../images/Lukisan_bg2About.jpeg"; // Gambar bawah teks
import img2 from "../images/lukiasn_prpirang.webp";
import img3 from "../images/Lukisan_wanitaddk.webp";
import "./About.css";

const About = ({props}) => {
  return (
    <div
      className="about-background"
      style={{
        backgroundImage: `url(${backgroundImage})`,
        backgroundSize: "cover",
        backgroundPosition: "center",
        backgroundRepeat: "no-repeat",
        width: "100%",
        height: "auto", // atau fix seperti 100vh jika ingin tingginya penuh
        paddingTop: "80px",
        paddingBottom: "80px",
      }}
    >
      <Container
        className="bg-white rounded p-5 shadow-lg"
        style={{ maxWidth: "900px" }}
      >
        <h1 className="text-center mb-4 fw-bold">PINCELA.</h1>

        <div className="d-flex justify-content-center mb-3">
          <span className="me-3">🎨 Choices</span>
          <span>🖼️ Full View</span>
        </div>

        <div
          className="px-md-5 px-2 py-4 border border-primary rounded bg-light text-center"
          style={{ lineHeight: "1.8" }}
        >
          <p>
            Pincela.com adalah sebuah ruang pamaran seni virtual yang
            didedikasikan untuk merayakan dan mengangkat karya-karya lukisan
            Indonesia yang telah memberikan warna dalam perjalanan seni rupa
            bangsa. Nama Pincela kami ambil dari interpretasi bebas kata
            "pincel" (kuas dalam bahasa Spanyol dan Portugis) yang kami
            sesuaikan menjadi Pincela, melambangkan kuas yang lembut namun
            kuat—alat sederhana yang mampu melahirkan karya besar.
          </p>
          <p>
            Di tangan para seniman, pincela adalah medium ekspresi, jembatan
            antara gagasan dan kanvas. Kami hadir untuk menjadi panggung digital
            bagi lukisan-lukisan legendaris Indonesia—karya-karya yang
            memancarkan semangat, perjalanan, cinta, kritik sosial, bahkan
            keheningan yang mendalam.
          </p>
          <p>
            Di era digital ini, kami percaya bahwa seni harus dapat dinikmati
            secara inklusif dan melintasi batas geografis. Karena itu, Pincela
            hadir tidak hanya sebagai galeri, tapi sebagai pengalaman: mengajak
            pengunjung untuk menyelami makna di balik warna, mengenal lebih
            dekat para seniman, serta menghargai keunikan teknik dan gaya yang
            menjadi kekayaan seni lukis Indonesia.
          </p>
        </div>

        {/* Gambar 3 di bawah teks */}
        <Row className="mt-5 text-center">
          <Col md={4} xs={12} className="mb-3">
            <Image src={img1} alt="Karya 1" fluid rounded />
          </Col>
          <Col md={4} xs={12} className="mb-3">
            <Image src={img2} alt="Karya 2" fluid rounded />
          </Col>
          <Col md={4} xs={12} className="mb-3">
            <Image src={img3} alt="Karya 3" fluid rounded />
          </Col>
        </Row>

        <div className="text-center mt-4">
          <button className="btn btn-outline-primary">
            {props}
          </button>
        </div>
      </Container>
    </div>
  );
};

export default About;
