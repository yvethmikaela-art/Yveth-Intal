export default function Footer() {
  const year = new Date().getFullYear();

  return (
    <footer style={styles.footer}>
      <div style={styles.inner}>
        <div style={styles.brand}>
          <div style={styles.dot} />
          <span style={styles.brandText}>MyApp</span>
        </div>
        <p style={styles.copy}>© {year} MyApp. All rights reserved.</p>
      </div>
    </footer>
  );
}

const styles = {
  footer: {
    borderTop: "1px solid #e8e6e0",
    background: "#fff",
    marginTop: "auto",
  },
  inner: {
    maxWidth: "1100px",
    margin: "0 auto",
    padding: "1.25rem 1.5rem",
    display: "flex",
    alignItems: "center",
    justifyContent: "space-between",
    gap: "1rem",
    flexWrap: "wrap",
  },
  brand: {
    display: "flex",
    alignItems: "center",
    gap: "8px",
  },
  dot: {
    width: "8px",
    height: "8px",
    borderRadius: "50%",
    background: "#1a1a1a",
  },
  brandText: {
    fontSize: "14px",
    fontWeight: "600",
    color: "#1a1a1a",
    letterSpacing: "-0.02em",
  },
  copy: {
    fontSize: "13px",
    color: "#aaa",
    margin: 0,
  },
};