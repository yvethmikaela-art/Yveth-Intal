export default function Dashboard({ user }) {
  return (
    <div style={styles.page}>
      <nav style={styles.nav}>
        <div style={styles.navLeft}>
          <div style={styles.navBrand}>
            <span style={styles.navBrandMain}>TECHNOPATH</span>
            <span style={styles.navBrandSub}>Solutions Inc.</span>
          </div>
        </div>
        <div style={styles.navRight}>
          <span style={styles.navUser}>Welcome, {user?.firstname ?? "User"}</span>
          <a href="/profile" style={styles.navLink}>Profile</a>
          <a href="/logout" style={styles.navBtn}>LOGOUT</a>
        </div>
      </nav>

      <div style={styles.hero}>
        <div style={styles.heroContent}>
          <div style={styles.heroAccentRow}>
            <div style={styles.accent} />
            <span style={styles.accentText}>DASHBOARD</span>
          </div>
          <h1 style={styles.heroHeading}>
            Good day, <span style={styles.heroName}>{user?.firstname ?? "there"}</span>
          </h1>
          <p style={styles.heroSub}>Signed in as <strong>{user?.email}</strong></p>
        </div>
      </div>

      <div style={styles.container}>
        <div style={styles.statsGrid}>
          {[
            { label: "Protection Status", value: "ACTIVE", color: "#27ae60" },
            { label: "Devices Protected", value: "—", color: "#1a1a2e" },
            { label: "Threats Blocked", value: "—", color: "#1a1a2e" },
            { label: "Last Login", value: "Today", color: "#1a1a2e" },
          ].map(({ label, value, color }) => (
            <div key={label} style={styles.statCard}>
              <span style={styles.statLabel}>{label}</span>
              <span style={{ ...styles.statValue, color }}>{value}</span>
            </div>
          ))}
        </div>

        <div style={styles.section}>
          <div style={styles.sectionHeader}>
            <div style={styles.accent} />
            <h2 style={styles.sectionTitle}>RECENT ACTIVITY</h2>
          </div>
          <div style={styles.emptyState}>
            <span style={styles.emptyIcon}>🛡️</span>
            <p style={styles.emptyText}>No recent activity. Your system is secure.</p>
          </div>
        </div>

        <div style={styles.infoBar}>
          <span>BSP REGULATED</span>
          <span style={styles.dot}>·</span>
          <span>PCI-DSS SECURE</span>
          <span style={styles.dot}>·</span>
          <span>PHILIPPINE HOSPITALS</span>
        </div>
      </div>
    </div>
  );
}

const styles = {
  page: { minHeight: "100vh", background: "#f5f5f5", fontFamily: "'Segoe UI', sans-serif" },
  nav: { background: "#fff", borderBottom: "1px solid #e0e0e0", padding: "12px 40px", display: "flex", alignItems: "center", justifyContent: "space-between" },
  navLeft: { display: "flex", alignItems: "center" },
  navBrand: { display: "flex", flexDirection: "column" },
  navBrandMain: { fontSize: "18px", fontWeight: "700", color: "#1a1a2e", letterSpacing: "2px" },
  navBrandSub: { fontSize: "11px", color: "#666" },
  navRight: { display: "flex", alignItems: "center", gap: "20px" },
  navUser: { fontSize: "13px", color: "#666" },
  navLink: { fontSize: "13px", color: "#1a1a2e", textDecoration: "none", fontWeight: "600" },
  navBtn: { background: "#c0392b", color: "#fff", padding: "10px 24px", borderRadius: "4px", textDecoration: "none", fontWeight: "700", fontSize: "13px", letterSpacing: "1px" },
  hero: { background: "#1a1a2e", padding: "3rem 40px" },
  heroContent: { maxWidth: "1100px", margin: "0 auto" },
  heroAccentRow: { display: "flex", alignItems: "center", gap: "10px", marginBottom: "1rem" },
  accent: { width: "32px", height: "3px", background: "#c0392b" },
  accentText: { fontSize: "12px", fontWeight: "700", color: "#c0392b", letterSpacing: "2px" },
  heroHeading: { fontSize: "36px", fontWeight: "700", color: "#fff", margin: "0 0 8px" },
  heroName: { color: "#c0392b" },
  heroSub: { fontSize: "14px", color: "#aaa", margin: 0 },
  container: { maxWidth: "1100px", margin: "0 auto", padding: "2rem 40px", display: "flex", flexDirection: "column", gap: "2rem" },
  statsGrid: { display: "grid", gridTemplateColumns: "repeat(auto-fit, minmax(200px, 1fr))", gap: "16px" },
  statCard: { background: "#fff", borderRadius: "8px", boxShadow: "0 2px 8px rgba(0,0,0,0.06)", padding: "1.5rem", display: "flex", flexDirection: "column", gap: "8px" },
  statLabel: { fontSize: "11px", fontWeight: "700", color: "#aaa", textTransform: "uppercase", letterSpacing: "1px" },
  statValue: { fontSize: "24px", fontWeight: "700", color: "#1a1a2e" },
  section: { background: "#fff", borderRadius: "8px", boxShadow: "0 2px 8px rgba(0,0,0,0.06)", padding: "2rem" },
  sectionHeader: { display: "flex", alignItems: "center", gap: "10px", marginBottom: "1.5rem" },
  sectionTitle: { fontSize: "13px", fontWeight: "700", color: "#1a1a2e", letterSpacing: "2px", margin: 0 },
  emptyState: { display: "flex", flexDirection: "column", alignItems: "center", padding: "2rem 0", gap: "8px" },
  emptyIcon: { fontSize: "32px" },
  emptyText: { fontSize: "14px", color: "#bbb", margin: 0 },
  infoBar: { display: "flex", alignItems: "center", justifyContent: "center", gap: "16px", fontSize: "11px", fontWeight: "700", color: "#aaa", letterSpacing: "1px", padding: "1rem 0" },
  dot: { color: "#ddd" },
};
