import { ImageResponse } from "next/og";

export const alt = "Dr. Malek Gaashan — Consultant Robotic Urological Surgeon";
export const size = { width: 1200, height: 630 };
export const contentType = "image/png";

export default function OpenGraphImage() {
  return new ImageResponse(
    (
      <div style={{ width: "100%", height: "100%", display: "flex", flexDirection: "column", justifyContent: "space-between", background: "#101D2C", color: "#F7F5F0", padding: 80, fontFamily: "serif" }}>
        <div style={{ display: "flex", alignItems: "center", gap: 20, color: "#B79A6A", fontSize: 22, letterSpacing: 6, textTransform: "uppercase", fontFamily: "sans-serif" }}>
          <div style={{ width: 60, height: 1, background: "#B79A6A" }} />
          Consultant Urological Surgeon
        </div>
        <div style={{ display: "flex", flexDirection: "column" }}>
          <div style={{ fontSize: 96, lineHeight: 1 }}>Dr. Malek Gaashan</div>
          <div style={{ marginTop: 24, fontSize: 30, color: "#CDB68F", fontFamily: "sans-serif", letterSpacing: 4 }}>MD · MRCS · FRCS (Urol)</div>
        </div>
        <div style={{ fontSize: 28, color: "rgba(247,245,240,0.6)", fontFamily: "sans-serif" }}>
          Robotic Surgery · Uro-Oncology · Intracorporeal Reconstruction
        </div>
      </div>
    ),
    size,
  );
}
