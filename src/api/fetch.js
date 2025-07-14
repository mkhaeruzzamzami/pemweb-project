const API_URL = "http://localhost/pincela/api";
export const API_UPLOAD_URL = "http://localhost/pincela/api/uploads";

// === CREATE ===
export async function sendSupportForm(data) {
  const formData = new FormData();
  for (let key in data) {
    if (data[key] !== undefined && data[key] !== null) {
      formData.append(key, data[key]);
    }
  }

  try {
    const res = await fetch(`${API_URL}/lukisan/create.php`, {
      method: "POST",
      body: formData,
    });

    const contentType = res.headers.get("content-type");
    if (contentType && contentType.includes("application/json")) {
      return await res.json();
    } else {
      const text = await res.text();
      console.warn("⚠️ Respon bukan JSON (CREATE):", text);
      throw new Error("Respon bukan JSON.");
    }
  } catch (err) {
    console.error("❌ Gagal CREATE:", err);
    return { status: "error", message: "Terjadi kesalahan saat menyimpan lukisan." };
  }
}

// === READ ===
export async function getAllLukisan() {
  try {
    const res = await fetch(`${API_URL}/lukisan/read.php`);
    const contentType = res.headers.get("content-type");

    if (contentType && contentType.includes("application/json")) {
      const result = await res.json();
      return result.status === "success" && Array.isArray(result.data) ? result.data : [];
    } else {
      const text = await res.text();
      console.warn("⚠️ Respon bukan JSON (READ):", text);
      return [];
    }
  } catch (error) {
    console.error("❌ Gagal fetch lukisan:", error);
    return [];
  }
}

// === UPDATE ===
export async function updateLukisan(data) {
  const formData = new FormData();

  if (data.id) formData.append("id", data.id);
  if (data.nama_lengkap) formData.append("nama_lengkap", data.nama_lengkap);
  if (data.email) formData.append("email", data.email);
  if (data.tema) formData.append("tema", data.tema);
  if (data.judul) formData.append("judul", data.judul);
  if (data.tanggal_pembuatan) formData.append("tanggal_pembuatan", data.tanggal_pembuatan);
  if (data.nama_pembuat) formData.append("nama_pembuat", data.nama_pembuat);
  if (data.deskripsi) formData.append("deskripsi", data.deskripsi);
  if (data.gambar instanceof File) formData.append("gambar", data.gambar);

  try {
    const res = await fetch(`${API_URL}/lukisan/update.php`, {
      method: "POST",
      body: formData,
    });

    const contentType = res.headers.get("content-type");
    if (contentType && contentType.includes("application/json")) {
      return await res.json();
    } else {
      const text = await res.text();
      console.warn("⚠️ Respon bukan JSON (UPDATE):", text);
      throw new Error("Respon tidak valid saat update lukisan.");
    }
  } catch (err) {
    console.error("❌ Gagal UPDATE:", err);
    return { status: "error", message: "Gagal update lukisan." };
  }
}

// === DELETE ===
export const deleteLukisan = async (id) => {
  try {
    const response = await fetch(`${API_URL}/lukisan/delete.php`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ id: parseInt(id) }),
    });

    const contentType = response.headers.get("content-type");
    if (contentType && contentType.includes("application/json")) {
      return await response.json();
    } else {
      const text = await response.text();
      console.warn("⚠️ Respon bukan JSON (DELETE):", text);
      throw new Error("Respon bukan JSON saat menghapus lukisan.");
    }
  } catch (error) {
    console.error("❌ Error saat delete:", error);
    return { status: "error", message: "Gagal menghapus lukisan." };
  }
};

// === LIKE ===
export async function sendLike(id) {
  try {
    const res = await fetch(`${API_URL}/lukisan/feedback_like.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ action: "like", id: parseInt(id) }),
    });

    const contentType = res.headers.get("content-type");
    if (contentType && contentType.includes("application/json")) {
      return await res.json();
    } else {
      const text = await res.text();
      console.warn("⚠️ Respon bukan JSON (LIKE):", text);
      throw new Error("Respon tidak valid saat mengirim like.");
    }
  } catch (err) {
    console.error("❌ Gagal like:", err);
    return { status: "error", message: "Gagal mengirim like." };
  }
}

// === COMMENT ===
export async function sendComment(id, nama, komentar) {
  try {
    const res = await fetch(`${API_URL}/lukisan/feedback_like.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ action: "comment", id: parseInt(id), nama, komentar }),
    });

    const contentType = res.headers.get("content-type");
    if (contentType && contentType.includes("application/json")) {
      return await res.json();
    } else {
      const text = await res.text();
      console.warn("⚠️ Respon bukan JSON (COMMENT):", text);
      throw new Error("Respon tidak valid saat kirim komentar.");
    }
  } catch (err) {
    console.error("❌ Gagal komentar:", err);
    return { status: "error", message: "Gagal mengirim komentar." };
  }
}

// === GET COMMENT ===
export async function getComments(id) {
  if (!id) {
    console.warn("❗ ID lukisan tidak valid");
    return [];
  }

  try {
    const res = await fetch(`${API_URL}/lukisan/feedback_like.php?id=${id}`);
    const contentType = res.headers.get("content-type");

    if (contentType && contentType.includes("application/json")) {
      const data = await res.json();
      return data.status === "success" ? data.comments || [] : [];
    } else {
      const text = await res.text();
      console.warn("⚠️ Respon bukan JSON (GET COMMENT):", text);
      throw new Error("Respon bukan JSON saat ambil komentar.");
    }
  } catch (error) {
    console.error("❌ Error ambil komentar:", error);
    return [];
  }
}
