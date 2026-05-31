import React, { useState } from "react";
import {
  LineChart, Line, BarChart, Bar, PieChart, Pie, Cell,
  XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer,
} from "recharts";
import {
  Wrench, AlertTriangle, Clock, TrendingUp,
  FileText, Package, Users, Star, ArrowDownRight,
} from "lucide-react";

/*
 | لوحة مؤشرات عاصمة الكون للمصاعد — FUJI-YEM Elevators
 | الهوية البصرية مستوحاة من الشعار: كحلي عميق + فضي معدني + فيروزي.
 | البيانات نموذجية؛ تُربط بـ /api/dashboard.
 */
const LOGO = "data:image/png;base64,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";

const SAMPLE = {
  overview: {
    open_requests: 23, unassigned: 5, in_progress: 11, completed_this_month: 64,
    revenue_this_month: 184500, outstanding: 96200, overdue_invoices: 7,
    active_contracts: 38, contracts_expiring: 4, low_stock_parts: 6,
  },
  requests_by_status: { new: 5, assigned: 7, in_progress: 11, completed: 9, closed: 142, cancelled: 6 },
  requests_by_type: { maintenance: 98, fault: 71, inspection: 31 },
  requests_trend: { "2025-06":14,"2025-07":18,"2025-08":22,"2025-09":19,"2025-10":25,"2025-11":28,"2025-12":24,"2026-01":31,"2026-02":27,"2026-03":34,"2026-04":30,"2026-05":38 },
  revenue_trend: { "2025-06":142000,"2025-07":138000,"2025-08":156000,"2025-09":149000,"2025-10":167000,"2025-11":172000,"2025-12":161000,"2026-01":178000,"2026-02":169000,"2026-03":188000,"2026-04":181000,"2026-05":184500 },
  financial_summary: { total_invoiced: 2140000, total_collected: 1843800, outstanding: 296200, collection_rate: 86.2 },
  problematic_elevators: [
    { elevator_id: 12, label: "المصعد الرئيسي", brand: "Mitsubishi", building: "فندق واحة المسك", faults: 9 },
    { elevator_id: 7, label: "مصعد رقم 2", brand: "FUJI", building: "برج النخيل", faults: 7 },
    { elevator_id: 21, label: "مصعد الخدمة", brand: "Otis", building: "مستشفى الرعاية", faults: 6 },
    { elevator_id: 4, label: "مصعد رقم 1", brand: "FUJI", building: "مجمع الأعمال", faults: 5 },
    { elevator_id: 18, label: "المصعد الشرقي", brand: "KONE", building: "فندق واحة المسك", faults: 4 },
  ],
  technician_performance: [
    { technician_id: 2, name: "خالد العتيبي", completed: 48, open: 4, avg_rating: 4.8, avg_hours: 3.2 },
    { technician_id: 5, name: "ماجد الدوسري", completed: 41, open: 3, avg_rating: 4.6, avg_hours: 4.1 },
    { technician_id: 3, name: "سعد القحطاني", completed: 37, open: 5, avg_rating: 4.4, avg_hours: 3.8 },
    { technician_id: 8, name: "نواف الشمري", completed: 29, open: 2, avg_rating: 4.2, avg_hours: 4.9 },
  ],
  top_consumed_parts: [
    { part_id: 15, sku: "MIT-RLR-08", name: "بكرة توجيه", qty: 34 },
    { part_id: 9, sku: "DR-SNS-22", name: "حساس باب", qty: 28 },
    { part_id: 31, sku: "BTN-LED-05", name: "زر طابق مضيء", qty: 25 },
    { part_id: 12, sku: "BLT-V-14", name: "سير محرك", qty: 19 },
    { part_id: 27, sku: "CBL-STL-6", name: "كابل فولاذي (متر)", qty: 16 },
  ],
  low_stock_parts: [
    { id: 12, sku: "BLT-V-14", name: "سير محرك", quantity_on_hand: 2, reorder_level: 5 },
    { id: 27, sku: "CBL-STL-6", name: "كابل فولاذي", quantity_on_hand: 8, reorder_level: 20 },
    { id: 9, sku: "DR-SNS-22", name: "حساس باب", quantity_on_hand: 3, reorder_level: 6 },
  ],
};

const C = {
  bg: "#0a1730", bg2: "#0d1f42",
  card: "#11244d", cardLine: "#1d3868",
  silver: "#c8cdd4", silverDim: "#8a98b5",
  turq: "#6fc3d8", royal: "#3b6fe0",
  white: "#f4f7fc", green: "#3ec98a", red: "#ff6b6b", amber: "#e0a93b",
};
const STATUS_AR = { new: "جديد", assigned: "مُكلّف", in_progress: "جارٍ", completed: "مكتمل", closed: "مغلق", cancelled: "ملغى" };
const TYPE_AR = { maintenance: "صيانة", fault: "عطل", inspection: "معاينة" };
const PIE = ["#3b6fe0", "#6fc3d8", "#c8cdd4", "#3ec98a", "#8a98b5", "#5a6b8c"];
const fmtSAR = (n) => new Intl.NumberFormat("ar-SA").format(n) + " ر.س";
const fmtMonth = (ym) => ["ينا","فبر","مار","أبر","ماي","يون","يول","أغس","سبت","أكت","نوف","ديس"][+ym.split("-")[1]-1];

export default function Dashboard() {
  const [data] = useState(SAMPLE);
  const o = data.overview;
  const trendData = Object.entries(data.requests_trend).map(([ym, v]) => ({ name: fmtMonth(ym), value: v }));
  const revenueData = Object.entries(data.revenue_trend).map(([ym, v]) => ({ name: fmtMonth(ym), value: v }));
  const statusData = Object.entries(data.requests_by_status).map(([k, v]) => ({ name: STATUS_AR[k] || k, value: v }));
  const typeData = Object.entries(data.requests_by_type).map(([k, v]) => ({ name: TYPE_AR[k] || k, value: v }));
  const tip = { background: C.card, border: `1px solid ${C.cardLine}`, borderRadius: 10, fontFamily: "inherit", color: C.white };

  return (
    <div dir="rtl" style={{ background: `radial-gradient(1200px 500px at 80% -10%, ${C.bg2}, ${C.bg})`, color: C.white, fontFamily: "'IBM Plex Sans Arabic', sans-serif", minHeight: "100vh" }}>
      <style>{`
        @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&display=swap');
        * { box-sizing: border-box; }
        .rise { animation: rise .55s cubic-bezier(.2,.7,.2,1) both; }
        @keyframes rise { from {opacity:0; transform: translateY(12px);} to {opacity:1; transform:none;} }
        .glow { box-shadow: 0 0 0 1px ${C.cardLine}, 0 10px 30px -12px rgba(0,0,0,.6); }
      `}</style>

      <header style={{ display: "flex", alignItems: "center", justifyContent: "space-between", padding: "18px 30px",
        background: "linear-gradient(180deg, rgba(30,80,200,.12), transparent)", borderBottom: `1px solid ${C.cardLine}` }}>
        <div style={{ display: "flex", alignItems: "center", gap: 16 }}>
          <img src={LOGO} alt="عاصمة الكون" style={{ height: 58, filter: "drop-shadow(0 4px 12px rgba(0,0,0,.5))" }} />
          <div style={{ borderRight: `1px solid ${C.cardLine}`, paddingRight: 16 }}>
            <div style={{ fontWeight: 700, fontSize: 18, color: C.white }}>لوحة التشغيل والمؤشرات</div>
            <div style={{ fontSize: 12.5, color: C.silverDim, letterSpacing: ".04em" }}>FUJI-YEM Elevators · وكلاء مصاعد فوجي</div>
          </div>
        </div>
        <div style={{ fontSize: 13, color: C.silverDim }}>{new Date().toLocaleDateString("ar-SA", { weekday: "long", day: "numeric", month: "long" })}</div>
      </header>

      <main style={{ padding: 28, maxWidth: 1280, margin: "0 auto" }}>
        <section style={{ display: "grid", gridTemplateColumns: "repeat(auto-fit,minmax(190px,1fr))", gap: 16, marginBottom: 26 }}>
          <Kpi icon={Wrench} color={C.turq} label="طلبات مفتوحة" value={o.open_requests} sub={`${o.unassigned} بانتظار التعيين`} d={0} />
          <Kpi icon={Clock} color={C.royal} label="قيد التنفيذ" value={o.in_progress} sub={`${o.completed_this_month} مكتمل هذا الشهر`} d={.05} />
          <Kpi icon={TrendingUp} color={C.green} label="إيراد الشهر" value={fmtSAR(o.revenue_this_month)} sub={`التحصيل ${data.financial_summary.collection_rate}%`} d={.1} small />
          <Kpi icon={ArrowDownRight} color={C.red} label="مبالغ متأخرة" value={fmtSAR(o.outstanding)} sub={`${o.overdue_invoices} فاتورة متأخرة`} d={.15} small />
          <Kpi icon={FileText} color={C.silver} label="عقود فعّالة" value={o.active_contracts} sub={`${o.contracts_expiring} تنتهي خلال 30 يوم`} d={.2} />
          <Kpi icon={Package} color={C.amber} label="قطع تحت الحد" value={o.low_stock_parts} sub="تحتاج إعادة طلب" d={.25} />
        </section>

        <section style={{ display: "grid", gridTemplateColumns: "1.6fr 1fr", gap: 16, marginBottom: 16 }}>
          <Panel title="اتجاه الطلبات (12 شهر)" d={.1}>
            <ResponsiveContainer width="100%" height={240}>
              <LineChart data={trendData} margin={{ top: 10, right: 8, left: -18, bottom: 0 }}>
                <CartesianGrid strokeDasharray="3 3" stroke={C.cardLine} />
                <XAxis dataKey="name" tick={{ fontSize: 12, fill: C.silverDim }} />
                <YAxis tick={{ fontSize: 12, fill: C.silverDim }} />
                <Tooltip contentStyle={tip} />
                <Line type="monotone" dataKey="value" name="طلبات" stroke={C.turq} strokeWidth={3} dot={{ r: 3, fill: C.turq }} activeDot={{ r: 6 }} />
              </LineChart>
            </ResponsiveContainer>
          </Panel>
          <Panel title="الطلبات حسب النوع" d={.15}>
            <ResponsiveContainer width="100%" height={240}>
              <PieChart>
                <Pie data={typeData} dataKey="value" nameKey="name" cx="50%" cy="50%" innerRadius={55} outerRadius={85} paddingAngle={3}>
                  {typeData.map((_, i) => <Cell key={i} fill={PIE[i % PIE.length]} stroke="none" />)}
                </Pie>
                <Tooltip contentStyle={tip} />
              </PieChart>
            </ResponsiveContainer>
            <Legend items={typeData} />
          </Panel>
        </section>

        <section style={{ display: "grid", gridTemplateColumns: "1.6fr 1fr", gap: 16, marginBottom: 16 }}>
          <Panel title="الإيرادات الشهرية" d={.2}>
            <ResponsiveContainer width="100%" height={240}>
              <BarChart data={revenueData} margin={{ top: 10, right: 8, left: 6, bottom: 0 }}>
                <CartesianGrid strokeDasharray="3 3" stroke={C.cardLine} />
                <XAxis dataKey="name" tick={{ fontSize: 12, fill: C.silverDim }} />
                <YAxis tick={{ fontSize: 11, fill: C.silverDim }} tickFormatter={(v) => `${v/1000}k`} />
                <Tooltip formatter={(v) => fmtSAR(v)} contentStyle={tip} />
                <Bar dataKey="value" name="إيراد" fill={C.royal} radius={[6, 6, 0, 0]} />
              </BarChart>
            </ResponsiveContainer>
          </Panel>
          <Panel title="الطلبات حسب الحالة" d={.25}>
            <ResponsiveContainer width="100%" height={240}>
              <PieChart>
                <Pie data={statusData} dataKey="value" nameKey="name" cx="50%" cy="50%" outerRadius={85}>
                  {statusData.map((_, i) => <Cell key={i} fill={PIE[i % PIE.length]} stroke="none" />)}
                </Pie>
                <Tooltip contentStyle={tip} />
              </PieChart>
            </ResponsiveContainer>
            <Legend items={statusData} />
          </Panel>
        </section>

        <section style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 16, marginBottom: 16 }}>
          <Panel title="المصاعد الأكثر تعطّلاً" d={.3} icon={AlertTriangle}>
            <table style={{ width: "100%", borderCollapse: "collapse", fontSize: 13.5 }}>
              <thead><tr style={{ color: C.silverDim, textAlign: "right" }}><Th>المصعد</Th><Th>المبنى</Th><Th>النوع</Th><Th>الأعطال</Th></tr></thead>
              <tbody>{data.problematic_elevators.map((e) => (
                <tr key={e.elevator_id} style={{ borderTop: `1px solid ${C.cardLine}` }}>
                  <Td><b style={{ color: C.white }}>{e.label}</b></Td><Td style={{ color: C.silver }}>{e.building}</Td>
                  <Td style={{ color: C.silverDim }}>{e.brand}</Td>
                  <Td><span style={{ background: "rgba(255,107,107,.15)", color: C.red, padding: "2px 11px", borderRadius: 20, fontWeight: 700 }}>{e.faults}</span></Td>
                </tr>))}</tbody>
            </table>
          </Panel>
          <Panel title="أداء الفنيين" d={.35} icon={Users}>
            <table style={{ width: "100%", borderCollapse: "collapse", fontSize: 13.5 }}>
              <thead><tr style={{ color: C.silverDim, textAlign: "right" }}><Th>الفني</Th><Th>منجز</Th><Th>مفتوح</Th><Th>التقييم</Th><Th>الزمن</Th></tr></thead>
              <tbody>{data.technician_performance.map((t) => (
                <tr key={t.technician_id} style={{ borderTop: `1px solid ${C.cardLine}` }}>
                  <Td><b style={{ color: C.white }}>{t.name}</b></Td><Td style={{ color: C.silver }}>{t.completed}</Td><Td style={{ color: C.silverDim }}>{t.open}</Td>
                  <Td><span style={{ display: "inline-flex", alignItems: "center", gap: 3, color: C.amber, fontWeight: 700 }}><Star size={13} fill={C.amber} />{t.avg_rating}</span></Td>
                  <Td style={{ color: C.silverDim }}>{t.avg_hours} س</Td>
                </tr>))}</tbody>
            </table>
          </Panel>
        </section>

        <section style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 16 }}>
          <Panel title="القطع الأكثر استهلاكاً" d={.4} icon={Package}>
            {data.top_consumed_parts.map((p, i) => {
              const max = data.top_consumed_parts[0].qty;
              return (
                <div key={p.part_id} style={{ marginBottom: 12 }}>
                  <div style={{ display: "flex", justifyContent: "space-between", fontSize: 13.5, marginBottom: 5 }}>
                    <span><b style={{ color: C.white }}>{p.name}</b> <span style={{ color: C.silverDim, fontSize: 12 }}>{p.sku}</span></span>
                    <b style={{ color: C.silver }}>{p.qty}</b>
                  </div>
                  <div style={{ height: 8, background: C.bg2, borderRadius: 6 }}>
                    <div style={{ height: "100%", width: `${(p.qty/max)*100}%`, background: PIE[i % PIE.length], borderRadius: 6 }} />
                  </div>
                </div>
              );
            })}
          </Panel>
          <Panel title="تنبيه: مخزون منخفض" d={.45} icon={AlertTriangle} alert>
            {data.low_stock_parts.map((p) => (
              <div key={p.id} style={{ display: "flex", justifyContent: "space-between", alignItems: "center", padding: "11px 0", borderBottom: `1px solid ${C.cardLine}` }}>
                <div><b style={{ fontSize: 14, color: C.white }}>{p.name}</b><div style={{ fontSize: 12, color: C.silverDim }}>{p.sku}</div></div>
                <div style={{ textAlign: "left" }}><span style={{ color: C.red, fontWeight: 700, fontSize: 15 }}>{p.quantity_on_hand}</span><span style={{ color: C.silverDim, fontSize: 12.5 }}> / حد {p.reorder_level}</span></div>
              </div>
            ))}
          </Panel>
        </section>

        <footer style={{ textAlign: "center", color: C.silverDim, fontSize: 12.5, marginTop: 28, paddingTop: 16, borderTop: `1px solid ${C.cardLine}` }}>
          عاصمة الكون للمصاعد — البيانات نموذجية للعرض · تُربط بـ <code style={{ background: C.card, padding: "1px 6px", borderRadius: 4, color: C.turq }}>/api/dashboard</code>
        </footer>
      </main>
    </div>
  );
}

function Kpi({ icon: Icon, color, label, value, sub, d, small }) {
  return (
    <div className="rise glow" style={{ animationDelay: `${d}s`, background: `linear-gradient(160deg, ${C.card}, ${C.bg2})`, borderRadius: 14, padding: 18 }}>
      <div style={{ display: "flex", justifyContent: "space-between", alignItems: "flex-start" }}>
        <div style={{ fontSize: 13, color: C.silverDim, fontWeight: 500 }}>{label}</div>
        <div style={{ width: 34, height: 34, borderRadius: 9, background: color + "22", display: "grid", placeItems: "center" }}><Icon size={18} color={color} /></div>
      </div>
      <div style={{ fontSize: small ? 21 : 30, fontWeight: 700, marginTop: 8, letterSpacing: "-.02em", color: C.white }}>{value}</div>
      <div style={{ fontSize: 12.5, color: C.silverDim, marginTop: 2 }}>{sub}</div>
    </div>
  );
}

function Panel({ title, children, d, icon: Icon, alert }) {
  return (
    <div className="rise glow" style={{ animationDelay: `${d}s`, background: `linear-gradient(160deg, ${C.card}, ${C.bg2})`, borderRadius: 14, padding: 20,
      boxShadow: alert ? `0 0 0 1px ${C.amber}55, 0 10px 30px -12px rgba(0,0,0,.6)` : undefined }}>
      <div style={{ display: "flex", alignItems: "center", gap: 8, marginBottom: 16, fontWeight: 700, fontSize: 15, color: C.white }}>
        {Icon && <Icon size={18} color={alert ? C.amber : C.turq} />}{title}
      </div>
      {children}
    </div>
  );
}

function Legend({ items }) {
  return (
    <div style={{ display: "flex", flexWrap: "wrap", gap: 12, justifyContent: "center", marginTop: 8 }}>
      {items.map((it, i) => (
        <span key={i} style={{ display: "flex", alignItems: "center", gap: 5, fontSize: 12.5, color: C.silverDim }}>
          <span style={{ width: 10, height: 10, borderRadius: 3, background: PIE[i % PIE.length] }} />{it.name} ({it.value})
        </span>
      ))}
    </div>
  );
}

const Th = ({ children }) => <th style={{ padding: "6px 4px", fontWeight: 600, fontSize: 12.5 }}>{children}</th>;
const Td = ({ children, style }) => <td style={{ padding: "9px 4px", ...style }}>{children}</td>;
