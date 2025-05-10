import React from "react";
import { Container } from "react-bootstrap";

const HeroSection = ({ title, subtitle }) => {
  return (
    <div className="hero-section">
      <Container>
        <h1>{title}</h1>
        <p className="lead">{subtitle}</p>
      </Container>
    </div>
  );
};

export default HeroSection; // Pastikan ada export default
