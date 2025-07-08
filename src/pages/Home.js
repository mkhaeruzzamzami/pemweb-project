import React, { useEffect } from "react";
import AOS from "aos";
import "aos/dist/aos.css";
import { useNavigate } from "react-router-dom";
import "./Home.css";

const Home = () => {
  const navigate = useNavigate();

  useEffect(() => {
    AOS.init({ duration: 1000 });
  }, []);

  const paintings = [
    {
      title: "Penangkapan Pangeran Diponegoro",
      image: "/images/Lukisan_Pangeran_Diponegoro.jpg",
    },
    {
      title: "A View of Mount Megamendung",
      image: "/images/Lukisan_Megamendung.webp",
    },
    {
      title: "Kawan – Kawan Revolusi",
      image: "/images/Lukisan_Kawan_Revolusi.jpg",
    },
    {
      title: "Nyi Roro Kidul",
      image: "/images/Lukisan_Nyirorokidul.webp",
    },
    {
      title: "Bali Life",
      image: "/images/Lukisan_Bali_Life.webp",
    },
    {
      title: "Pemburuan Rusa",
      image: "/images/Lukisan_Pemburuan_Rusa.jpg",
    },
  ];

  return (
    <div
      className="home-page"
      onClick={() => navigate("/collections")}
      style={{ cursor: "pointer" }}
    >
      {/* Hero Section */}
      <div className="hero-section">
        <div className="container">
          <h1 className="hero-title">Pincela.</h1>
          <p className="hero-subtitle">
            Pincela adalah web pameran lukisan digital yang menampilkan karya
            seni dari berbagai seniman dalam tampilan galeri yang elegan dan
            sangat menarik
          </p>
        </div>
      </div>

      {/* Infinite Scroll Gallery */}
      <div className="scroll-gallery-wrapper">
        <div className="scroll-gallery">
          {[...paintings, ...paintings].map((painting, index) => (
            <div className="scroll-image" key={index}>
              <img src={painting.image} alt={painting.title} />
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

export default Home;
