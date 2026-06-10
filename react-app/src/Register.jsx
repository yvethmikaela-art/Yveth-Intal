import { useState } from "react";

export default function Register() {
  const [form, setForm] = useState({
    firstname: "", lastname: "", email: "", password: "", password_confirm: "",
  });
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");
    if (form.password !== form.password_confirm) {
      setError("Passwords do not match.");
      return;
    }
    setLoading(true);
    try {
      const res = await fetch("/register", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams(form),
      });
      if (res.redirected) {
        window.location.href = res.url;
      } else {
        setError("Registration failed. Please try again.");
      }
    } catch {
      setError("Something went wrong. Please try again.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div style={styles.page}>
      <nav style={styles.nav}>
        <div style={styles.navLeft}>
          <div style={styles.navBrand}>
            <span style={styles.navBrandMain}>TECHNOPATH</span>
            <span style={styles.navBrandSub}>Solutions Inc.</span>
          </div>
        </div>
        <a href="/" style={styles.navBtn}>SIGN IN</a>
      </nav>

      <div style={styles.container}>
        <div style={styles.card}>
          <div style={styles.cardHeader}>
            <div style={styles.accent} />
            <span style={styles.accentText}>CREATE ACCOUNT</span>
          </div>
          <h1 style={styles.heading}>Get Protected Today</h1>
          <p style={styles.subheading}>Register your Mt. Grace Protect account</p>

          {error && <div style={styles.errorBox}>{error}</div>}

          <form onSubmit={handleSubmit} style={styles.form}>
            <div style={styles.row}>
              <div style={styles.field}>
                <label style={styles.label}>First Name</label>
                <input
                  type="text"
                  name="firstname"
                  value={form.firstname}
                  onChange={handleChange}
                  placeholder="Juan"
                  required
                  style={styles.input}
                />
              </div>
              <div style={styles.field}>
                <label style={styles.label}>Last Name</label>
                <input
                  type="text"
                  name="lastname"
                  value={form.lastname}
                  onChange={handleChange}
                  placeholder="dela Cruz"
                  required
                  style={styles.input}
                />
              </div>
            </div>

            <div style={styles.field}>
              <label style={styles.label}>Email Address</label>
              <input
                type="email"
                name="email"
                value={form.email}
                onChange={handleChange}
                placeholder="you@example.com"
                required
                style={styles.input}
              />
            </div>

            <div style={styles.field}>
              <label style={styles.label}>Password</label>
              <input
                type="password"
                name="password"
                value={form.password}
                onChange={handleChange}
                placeholder="••••••••"
                required
                style={styles.input}
              />
            </div>

            <div style={styles.field}>
              <label style={styles.label}>Confirm Password</label>
              <input
                type="password"
                name="password_confirm"
                value={form.password_confirm}
                onChange={handleChange}
                placeholder="••••••••"
                required
                style={styles.input}
              />
            </div>

            <button type="submit" disabled={loading} style={styles.button}>
              {loading ? "CREATING ACCOUNT…" : "CREATE ACCOUNT"}
            </button>
          </form>

          <p style={styles.footer}>
            Already have an account?{" "}
            <a href="/" style={styles.link}>Sign in here</a>
          </p>
        </div>
      </div>
    </div>
  );
}

const styles = {
  page: { minHeight: "100vh", background: "#f5f5f5", fontFamily: "'Segoe UI', sans-serif" },
  nav: { background: "#fff", borderBottom: "1px solid #e0e0e0", padding: "12px 40px", display: "flex", alignItems: "center", justifyContent: "space-between" },
  navLeft: { display: "flex", alignItems: "center", gap: "12px" },
  navBrand: { display: "flex", flexDirection: "column" },
  navBrandMain: { fontSize: "18px", fontWeight: "700", color: "#1a1a2e", letterSpacing: "2px" },
  navBrandSub: { fontSize: "11px", color: "#666" },
  navBtn: { background: "#1a1a2e", color: "#fff", padding: "10px 24px", borderRadius: "4px", textDecoration: "none", fontWeight: "700", fontSize: "13px", letterSpacing: "1px" },
  container: { display: "flex", alignItems: "center", justifyContent: "center", minHeight: "calc(100vh - 73px)", padding: "2rem" },
  card: { background: "#fff", borderRadius: "8px", boxShadow: "0 4px 24px rgba(0,0,0,0.1)", padding: "3rem", width: "100%", maxWidth: "500px" },
  cardHeader: { display: "flex", alignItems: "center", gap: "10px", marginBottom: "1.5rem" },
  accent: { width: "32px", height: "3px", background: "#c0392b" },
  accentText: { fontSize: "12px", fontWeight: "700", color: "#c0392b", letterSpacing: "2px" },
  heading: { fontSize: "28px", fontWeight: "700", color: "#1a1a2e", margin: "0 0 8px", letterSpacing: "-0.5px" },
  subheading: { fontSize: "14px", color: "#888", margin: "0 0 2rem" },
  errorBox: { background: "#fef2f2", border: "1px solid #fecaca", color: "#b91c1c", borderRadius: "6px", padding: "10px 14px", fontSize: "13px", marginBottom: "1.25rem" },
  form: { display: "flex", flexDirection: "column", gap: "1.25rem" },
  row: { display: "grid", gridTemplateColumns: "1fr 1fr", gap: "12px" },
  field: { display: "flex", flexDirection: "column", gap: "6px" },
  label: { fontSize: "13px", fontWeight: "600", color: "#1a1a2e", textTransform: "uppercase", letterSpacing: "0.5px" },
  input: { padding: "12px 14px", border: "1px solid #ddd", borderRadius: "6px", fontSize: "14px", color: "#1a1a2e", outline: "none", width: "100%", boxSizing: "border-box" },
  button: { marginTop: "0.5rem", padding: "14px", background: "#c0392b", color: "#fff", border: "none", borderRadius: "6px", fontSize: "13px", fontWeight: "700", cursor: "pointer", letterSpacing: "1px" },
  footer: { marginTop: "1.5rem", fontSize: "13px", color: "#888", textAlign: "center" },
  link: { color: "#c0392b", fontWeight: "600", textDecoration: "none" },
};
