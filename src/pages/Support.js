import React, { useState, useEffect } from "react";
import { Container, Form, Button, Row, Col, Card } from "react-bootstrap";
import supportImage from "../images/Lukisan_coretabstrak.png";
import {
  sendSupportForm,
  getAllLukisan,
  updateLukisan,
  deleteLukisan,
} from "../api/fetch";

const Support = () => {
  const [formData, setFormData] = useState({
    nama_lengkap: "",
    tema: "",
    judul: "",
    tanggal_pembuatan: "",
    email: "",
    nama_pembuat: "",
    deskripsi: "",
    gambar: null,
  });

  const [previewImage, setPreviewImage] = useState(null);
  const [lukisanList, setLukisanList] = useState([]);
  const [editId, setEditId] = useState(null);

  useEffect(() => {
    fetchData();
  }, []);

  const fetchData = async () => {
    const data = await getAllLukisan();
    setLukisanList(data);
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({
      ...prev,
      [name]: value,
    }));
  };

  const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      setFormData((prev) => ({
        ...prev,
        gambar: file,
      }));
      setPreviewImage(URL.createObjectURL(file));
    }
  };

  const resetForm = () => {
    setFormData({
      nama_lengkap: "",
      tema: "",
      judul: "",
      tanggal_pembuatan: "",
      email: "",
      nama_pembuat: "",
      deskripsi: "",
      gambar: null,
    });
    setPreviewImage(null);
    setEditId(null);
  };

  const validateForm = () => {
    const requiredFields = [
      "nama_lengkap",
      "tema",
      "judul",
      "tanggal_pembuatan",
      "email",
      "nama_pembuat",
    ];
    for (let field of requiredFields) {
      if (!formData[field]) return false;
    }
    return true;
  };

  const handleSubmitCreate = async (e) => {
    e.preventDefault();
    if (!validateForm()) {
      alert("❌ Semua kolom wajib diisi.");
      return;
    }

    try {
      const response = await sendSupportForm(formData);
      if (response.status === "success") {
        alert("✅ Lukisan ditambahkan!");
        resetForm();
        fetchData();
      } else {
        alert("❌ Gagal: " + response.message);
      }
    } catch (err) {
      console.error("❌ Gagal kirim:", err);
      alert("❌ Terjadi kesalahan saat mengirim data.");
    }
  };

  const handleSubmitUpdate = async (e) => {
    e.preventDefault();
    if (!validateForm()) {
      alert("❌ Semua kolom wajib diisi.");
      return;
    }

    try {
      const payload = { ...formData, id: editId };
      const response = await updateLukisan(payload);
      if (response.status === "success") {
        alert("✅ Lukisan diupdate!");
        resetForm();
        fetchData();
      } else {
        alert("❌ Gagal update: " + response.message);
      }
    } catch (err) {
      console.error("❌ Error saat update:", err);
      alert("❌ Terjadi kesalahan saat update.");
    }
  };

  const handleEdit = (item) => {
    setFormData({
      nama_lengkap: item.nama_lengkap || "",
      tema: item.tema || "",
      judul: item.judul || "",
      tanggal_pembuatan: item.tanggal_pembuatan || "",
      email: item.email || "",
      nama_pembuat: item.nama_pembuat || "",
      deskripsi: item.deskripsi || "",
      gambar: null,
    });
    setPreviewImage(item.gambar_url ? `http://localhost/pincela/api/uploads/${item.gambar_url}` : null);
    setEditId(item.id);
  };

  const handleDelete = async (id) => {
    if (window.confirm("Yakin mau hapus lukisan ini?")) {
      try {
        const response = await deleteLukisan(id);
        if (response.status === "success") {
          alert("✅ Lukisan dihapus!");
          fetchData();
        } else {
          alert("❌ Gagal hapus: " + response.message);
        }
      } catch (err) {
        console.error("❌ Gagal hapus:", err);
        alert("❌ Terjadi kesalahan saat menghapus.");
      }
    }
  };

  return (
    <Container className="py-5">
      <Row className="mb-5">
        <Col lg={5} className="d-none d-lg-block">
          <img
            src={supportImage}
            alt="Ilustrasi Lukisan"
            className="img-fluid rounded"
          />
        </Col>

        <Col lg={7}>
          <h3 className="mb-4">
            {editId ? "Edit Lukisan" : "Tambah Lukisan Baru"}
          </h3>
          <Form onSubmit={editId ? handleSubmitUpdate : handleSubmitCreate}>
            <Form.Group className="mb-3">
              <Form.Label>Nama Lengkap</Form.Label>
              <Form.Control
                type="text"
                name="nama_lengkap"
                value={formData.nama_lengkap}
                onChange={handleChange}
                required
              />
            </Form.Group>

            <Form.Group className="mb-3">
              <Form.Label>Tema Lukisan</Form.Label>
              <Form.Control
                type="text"
                name="tema"
                value={formData.tema}
                onChange={handleChange}
                required
              />
            </Form.Group>

            <Form.Group className="mb-3">
              <Form.Label>Judul Lukisan</Form.Label>
              <Form.Control
                type="text"
                name="judul"
                value={formData.judul}
                onChange={handleChange}
                required
              />
            </Form.Group>

            <Form.Group className="mb-3">
              <Form.Label>Tanggal Pembuatan</Form.Label>
              <Form.Control
                type="date"
                name="tanggal_pembuatan"
                value={formData.tanggal_pembuatan}
                onChange={handleChange}
                required
              />
            </Form.Group>

            <Form.Group className="mb-3">
              <Form.Label>Email</Form.Label>
              <Form.Control
                type="email"
                name="email"
                value={formData.email}
                onChange={handleChange}
                required
              />
            </Form.Group>

            <Form.Group className="mb-3">
              <Form.Label>Nama Pembuat</Form.Label>
              <Form.Control
                type="text"
                name="nama_pembuat"
                value={formData.nama_pembuat}
                onChange={handleChange}
                required
              />
            </Form.Group>

            <Form.Group className="mb-3">
              <Form.Label>Deskripsi Lukisan</Form.Label>
              <Form.Control
                as="textarea"
                name="deskripsi"
                value={formData.deskripsi}
                onChange={handleChange}
                rows={3}
              />
            </Form.Group>

            <Form.Group className="mb-3">
              <Form.Label>Upload Gambar Lukisan</Form.Label>
              <Form.Control
                type="file"
                accept="image/*"
                onChange={handleImageChange}
              />
            </Form.Group>

            {previewImage && (
              <div className="mb-3">
                <img
                  src={previewImage}
                  alt="Preview"
                  className="img-fluid rounded"
                  style={{ maxHeight: "200px" }}
                />
              </div>
            )}

            <div className="d-flex justify-content-between">
              <Button type="submit" variant="primary">
                {editId ? "Update Lukisan" : "Simpan Lukisan"}
              </Button>
              {editId && (
                <Button type="button" variant="secondary" onClick={resetForm}>
                  Batal Edit
                </Button>
              )}
            </div>
          </Form>
        </Col>
      </Row>

      <hr className="my-5" />

      <h3 className="mb-4">Daftar Lukisan</h3>
      <Row>
        {lukisanList.map((item) => (
          <Col md={6} lg={4} key={item.id} className="mb-4">
            <Card>
              {item.gambar_url && (
                <Card.Img
                  variant="top"
                  src={item.gambar_url}
                  style={{ height: "200px", objectFit: "cover" }}
                />
              )}
              <Card.Body>
                <h5>{item.judul}</h5>
                <p><strong>Tema:</strong> {item.tema}</p>
                <p><strong>Nama:</strong> {item.nama_lengkap}</p>
                <p><strong>Email:</strong> {item.email}</p>
                <p><strong>Pembuat:</strong> {item.nama_pembuat}</p>
                <p><strong>Tanggal:</strong> {item.tanggal_pembuatan}</p>
                <p><strong>Deskripsi:</strong> {item.deskripsi}</p>
              </Card.Body>
              <Card.Footer className="d-flex justify-content-between">
                <Button variant="warning" size="sm" onClick={() => handleEdit(item)}>
                  Edit
                </Button>
                <Button variant="danger" size="sm" onClick={() => handleDelete(item.id)}>
                  Hapus
                </Button>
              </Card.Footer>
            </Card>
          </Col>
        ))}
      </Row>
    </Container>
  );
};

export default Support;
