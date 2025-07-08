import React, { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { Container, Card, Button, Form } from "react-bootstrap";
import { getAllLukisan } from "../api/fetch";

const staticPaintings = [
  {
    id: "offline-1",
    title: "A View of Mount Megamendung",
    image: "/images/Lukisan_Pangeran_Diponegoro.jpg",
    description:
      "Lukisan pemandangan gunung megamendung yang menggambarkan keindahan alam dan kekuatan sejarah lokal.",
  },
  {
    id: "offline-2",
    title: "The Ruins and The Piano",
    image: "/images/Lukisan_Pemburuan_Rusa.webp",
    description:
      "Sebuah lukisan kontemporer yang menunjukkan kehancuran dan harmoni melalui piano klasik.",
  },
  {
    id: "offline-3",
    title: "Pasukan Kita di Bawah Pimpinan Panglima Diponegoro",
    image: "/images/Lukisan_Megamendung.webp",
    description:
      "Sebuah karya heroik yang menggambarkan semangat perjuangan pasukan Pangeran Diponegoro.",
  },
  {
    id: "offline-4",
    title: "The Card Players karya Paul Cezanne (1892)",
    image: "/images/Lukisan_Theruins_ThePiano.webp",
    description:
      "Lukisan klasik yang menggambarkan kehidupan sosial dan permainan kartu abad ke-19.",
  },
];

const GalleryItem = () => {
  const { id } = useParams();
  const [lukisan, setLukisan] = useState(null);
  const [likes, setLikes] = useState(0);
  const [comments, setComments] = useState([]);
  const [newComment, setNewComment] = useState("");
  const [showComments, setShowComments] = useState(false);

  useEffect(() => {
    const fetchData = async () => {
      const staticData = staticPaintings.find((item) => item.id === id);
      if (staticData) {
        setLukisan({ ...staticData, isOffline: true });
        return;
      }

      const data = await getAllLukisan();
      const found = data.find((item) => String(item.id) === id);
      if (found) {
        setLukisan({
          title: found.judul,
          image: found.gambar_url,
          description: found.deskripsi,
          tema: found.tema,
          pembuat: found.nama_pembuat,
          tanggal: found.tanggal_pembuatan,
          isOffline: false,
        });
      } else {
        setLukisan(null);
      }
    };

    fetchData();
  }, [id]);

  const handleShare = () => {
    if (navigator.share) {
      navigator
        .share({
          title: lukisan.title,
          text: "Cek lukisan keren ini di Pincela!",
          url: window.location.href,
        })
        .catch((err) => console.error("Gagal share:", err));
    } else {
      alert("Browser tidak mendukung fitur share.");
    }
  };

  const handleAddComment = (e) => {
    e.preventDefault();
    if (newComment.trim()) {
      const newEntry = {
        user: "User",
        text: newComment.trim(),
      };
      setComments([...comments, newEntry]);
      setNewComment("");
    }
  };

  if (!lukisan) {
    return <p className="text-center my-5">Lukisan tidak ditemukan.</p>;
  }

  return (
    <Container className="my-5 text-center">
      <Card className="mx-auto" style={{ maxWidth: "600px" }}>
        <Card.Img variant="top" src={lukisan.image} alt={lukisan.title} />
        <Card.Body>
          <Card.Title>{lukisan.title}</Card.Title>
          {lukisan.tema && (
            <Card.Text>
              <strong>Tema:</strong> {lukisan.tema}
            </Card.Text>
          )}
          {lukisan.pembuat && (
            <Card.Text>
              <strong>Pembuat:</strong> {lukisan.pembuat}
            </Card.Text>
          )}
          {lukisan.tanggal && (
            <Card.Text>
              <strong>Tanggal:</strong> {lukisan.tanggal}
            </Card.Text>
          )}
          <Card.Text>{lukisan.description}</Card.Text>

          {/* Horizontal: Like, Comment, Share */}
          <div className="d-flex gap-3 justify-content-center mb-3">
            <Button variant="outline-danger" onClick={() => setLikes(likes + 1)}>
              ❤️ Like ({likes})
            </Button>

            <Button
              variant="outline-primary"
              onClick={() => setShowComments(!showComments)}
            >
              💬 Comment
            </Button>

            <Button variant="outline-success" onClick={handleShare}>
              🔗 Share
            </Button>
          </div>

          {/* Comment bubble: muncul kalau showComments true */}
          {showComments && (
            <div
              className="text-start p-3 border rounded shadow-sm mb-3"
              style={{ background: "#f8f9fa" }}
            >
              <Form onSubmit={handleAddComment} className="mb-3">
                <Form.Group>
                  <Form.Control
                    type="text"
                    placeholder="Tulis komentar..."
                    value={newComment}
                    onChange={(e) => setNewComment(e.target.value)}
                  />
                </Form.Group>
                <Button type="submit" variant="primary" className="mt-2">
                  Kirim
                </Button>
              </Form>

              {comments.length > 0 && (
                <div className="mt-3">
                  <h6>Komentar:</h6>
                  {comments.map((comment, idx) => (
                    <div key={idx} className="d-flex align-items-start mb-2">
                      <div
                        className="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center"
                        style={{ width: "30px", height: "30px", fontSize: "0.8rem" }}
                      >
                        U
                      </div>
                      <div className="ms-2">
                        <strong>User</strong>
                        <div>{comment.text}</div>
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </div>
          )}
        </Card.Body>
      </Card>
    </Container>
  );
};

export default GalleryItem;
