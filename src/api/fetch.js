const API_URL = "http://localhost/pemweb-project-main/pincela/api";
export const API_UPLOAD_URL = "http://localhost/pemweb-project-main/pincela/api/uploads";

// === CREATE ===
export async function sendSupportForm(data) {
  const formData = new FormData();
  for (let key in data) {
    formData.append(key, data[key]);
  }

  try {
    const res = await fetch(`${API_URL}/lukisan/index.php`, {
      method: "POST",
      body: formData,
    });

    const text = await res.text();
    return JSON.parse(text);
  } catch (err) {
    console.error("Gagal parse JSON:", err);
    return { status: "error", message: "Terjadi kesalahan saat mengirim data." };
  }
}

// === READ ===
export async function getAllLukisan() {
  try {
    const response = await fetch(`${API_URL}/lukisan/read.php`); // GET aja cukup
    const result = await response.json();

    if (result.status === "success" && Array.isArray(result.data)) {
      return result.data;
    } else {
      console.error("Data lukisan tidak valid:", result);
      return [];
    }
  } catch (error) {
    console.error("Gagal fetch lukisan:", error);
    return [];
  }
}

// === UPDATE ===
export async function updateLukisan(data) {
  const formData = new FormData();
  for (let key in data) {
    formData.append(key, data[key]);
  }

  try {
    const res = await fetch(`${API_URL}/lukisan/update.php`, {
      method: "POST",
      body: formData,
    });

    return await res.json();
  } catch (err) {
    console.error("Gagal update lukisan:", err);
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
      body: JSON.stringify({ id }),
    });

    const data = await response.json();
    return data;
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
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ action: "like", id }),
    });

    return await res.json();
  } catch (err) {
    console.error("Gagal like:", err);
    return { status: "error", message: "Gagal mengirim like." };
  }
}

// === COMMENT ===
export async function sendComment(id, nama, komentar) {
  try {
    const res = await fetch(`${API_URL}/lukisan/feedback_like.php`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        action: "comment",
        id: parseInt(id),
        nama,
        komentar,
      }),
    });

    return await res.json();
  } catch (err) {
    console.error("Gagal kirim komentar:", err);
    return { status: "error", message: "Gagal mengirim komentar." };
  }
}

// === GET COMMENT ===
export async function getComments(id) {
  try {
    const res = await fetch(`${API_URL}/lukisan/comment.php?id=${id}`);
    if (!res.ok) throw new Error("Gagal mengambil komentar.");

    const data = await res.json();
    if (data.status === "success") {
      return data.comments || [];
    } else {
      console.warn("Komentar gagal diambil:", data.message);
      return [];
    }
  } catch (error) {
    console.error("Error ambil komentar:", error);
    return [];
  }
}
