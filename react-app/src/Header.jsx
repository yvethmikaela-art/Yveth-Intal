export default function Header({ user }) {
  const initials = user
    ? `${user.firstname?.[0] ?? ""}${user.lastname?.[0] ?? ""}`.toUpperCase()
    : "?";

  const handleLogout = async () => {
    await fetch("/logout", { method: "GET", headers: { "X-Requested-With": "XMLHttpRequest" } });
    window.location.href = "/";
  };

  return (
    <header style={styles.header}>
      <div style={styles.inner}>
        <a href="/dashboard" style={styles.brand}>
          <div style={styles.dot} />
          <span style={styles.brandText}>MyApp</span>
        </a>

        <nav style={styles.nav}>
          <a href="/dashboard" style={styles.navLink}>Dashboard</a>
          <a href="/profile" style={styles.navLink}>Profile</a>
        </nav>

        <div style={styles.right}>
          <div style={styles.avatar} title={`${user?.firstname} ${user?.lastname}`}>
            {initials}
          </div>
          <button onClick={handleLogout} style={styles.logoutBtn}>
            Sign out
          </button>
        </div>
      </div>
    </header>
  );
}

const styles = {
  header: {
    borderBottom: "1px solid #e8e6e0",
    background: "#fff",
    position: "sticky",
    top: 0,
    zIndex: 100,
  },
  inner: {
    maxWidth: "1100px",
    margin: "0 auto",
    padding: "0 1.5rem",
    height: "56px",
    display: "flex",
    alignItems: "center",
    gap: "2rem",
  },
  brand: {
    display: "flex",
    alignItems: "center",
    gap: "8px",
    textDecoration: "none",
  },
  dot: {
    width: "10px",
    height: "10px",
    borderRadius: "50%",
    background: "#1a1a1a",
  },
  brandText: {
    fontSize: "15px",
    fontWeight: "600",
    color: "#1a1a1a",
    letterSpacing: "-0.02em",
  },
  nav: {
    display: "flex",
    gap: "1.5rem",
    flex: 1,
  },
  navLink: {
    fontSize: "14px",
    color: "#666",
    textDecoration: "none",
    fontWeight: "400",
    transition: "color 0.15s",
  },
  right: {
    display: "flex",
    alignItems: "center",
    gap: "12px",
    marginLeft: "auto",
  },
  avatar: {
    width: "32px",
    height: "32px",
    borderRadius: "50%",
    background: "#1a1a1a",
    color: "#fff",
    display: "flex",
    alignItems: "center",
    justifyContent: "center",
    fontSize: "12px",
    fontWeight: "600",
    letterSpacing: "0.02em",
    flexShrink: 0,
  },
  logoutBtn: {
    background: "none",
    border: "1px solid #e2e0da",
    borderRadius: "7px",
    padding: "6px 12px",
    fontSize: "13px",
    color: "#444",
    cursor: "pointer",
    fontFamily: "inherit",
    transition: "border-color 0.15s, color 0.15s",
  },
};