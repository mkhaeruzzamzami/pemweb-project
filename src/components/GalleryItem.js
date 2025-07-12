import React, { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { Container, Card, Button, Form } from "react-bootstrap";
import { getAllLukisan, sendComment, sendLike, getComments } from "../api/fetch";

const GalleryItem = () => {
  const { id } = useParams();
  const [lukisan, setLukisan] = useState(null);
  const [likes, setLikes] = useState(0);
  const [comments, setComments] = useState([]);
  const [newComment, setNewComment] = useState("");
  const [showComments, setShowComments] = useState(false);

  useEffect(() => {
    const fetchData = async () => {
      const data = await getAllLukisan();
      const found = data.find((item) => String(item.id) === id);

      if (found) {
        setLukisan({
          id: found.id,
          title: found.judul,
          image: found.gambar_url, // langsung pakai dari database
          description: found.deskripsi,
          tema: found.tema,
          pembuat: found.nama_pembuat,
          tanggal: found.tanggal_pembuatan,
        });
        setLikes(found.likes || 0);

        const komentar = await getComments(found.id);
        setComments(komentar);
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

  const handleAddComment = async (e) => {
    e.preventDefault();

    const trimmed = newComment.trim();
    if (!trimmed) return;

    const nama = "User"; // placeholder
    const komentar = trimmed;

    try {
      const res = await sendComment(lukisan.id, nama, komentar);
      if (res.status === "success") {
        setComments((prev) => [...prev, { nama, komentar }]);
        setNewComment("");
      } else {
        console.warn("Gagal menyimpan komentar:", res.message);
      }
    } catch (error) {
      console.error("Gagal kirim komentar ke backend:", error);
    }
  };

  const handleLike = async () => {
    try {
      const res = await sendLike(lukisan.id);
      if (res.status === "success") {
        const data = await getAllLukisan();
        const updated = data.find((item) => String(item.id) === id);
        if (updated && updated.likes !== undefined) {
          setLikes(updated.likes);
        }
      } else {
        console.warn("Gagal menyimpan like ke backend:", res.message);
      }
    } catch (error) {
      console.error("Error saat mengirim like:", error);
    }
  };

  if (!lukisan) {
    return <p className="text-center my-5">Lukisan tidak ditemukan.</p>;
  }

  return (
    <Container className="my-5 text-center">
      <Card className="mx-auto shadow" style={{ maxWidth: "600px" }}>
        <Card.Img
          variant="top"
          src={lukisan.image}
          alt={lukisan.title}
          onError={(e) => {
            e.target.onerror = null;
            e.target.src = "/images/fallback.jpg";
          }}
        />
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

          <div className="d-flex gap-3 justify-content-center mb-3">
            <Button variant="outline-danger" onClick={handleLike}>
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
                    <div
                      key={idx}
                      className="d-flex align-items-start mb-2"
                    >
                      <div
                        className="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center"
                        style={{
                          width: "30px",
                          height: "30px",
                          fontSize: "0.8rem",
                        }}
                      >
                        {comment.nama[0]?.toUpperCase() || "?"}
                      </div>
                      <div className="ms-2">
                        <strong>{comment.nama}</strong>
                        <div>{comment.komentar}</div>
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
