<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8"/>
    <title>Hedge Fee Calculator – XAUUSD</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <!-- Có thể chạy offline, không cần CDN -->
    <style>
        :root {
            --bg: #0f172a;
            --bg-soft: #111827;
            --card: #020617;
            --accent: #38bdf8;
            --accent-soft: rgba(56, 189, 248, 0.12);
            --text: #e5e7eb;
            --muted: #9ca3af;
            --danger: #f97373;
            --radius: 14px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: radial-gradient(circle at top, #1f2937 0, #020617 55%);
            color: var(--text);
            min-height: 100vh;
        }

        .page {
            max-width: 1080px;
            margin: 0 auto;
            padding: 24px 16px 40px;
        }

        h1 {
            font-size: 1.8rem;
            margin: 0 0 4px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        h1 span.logo {
            width: 32px;
            height: 32px;
            border-radius: 999px;
            background: radial-gradient(circle at 30% 20%, #fde68a, #f97316);
            display: inline-block;
        }

        .subtitle {
            color: var(--muted);
            font-size: 0.9rem;
            margin-bottom: 18px;
        }

        .layout {
            display: grid;
            grid-template-columns: minmax(0, 1.6fr) minmax(0, 1.1fr);
            gap: 18px;
        }

        @media (max-width: 900px) {
            .layout {
                grid-template-columns: minmax(0, 1fr);
            }
        }

        .card {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.96), rgba(15, 23, 42, 0.9));
            border-radius: var(--radius);
            border: 1px solid rgba(148, 163, 184, 0.3);
            padding: 18px 18px 16px;
            box-shadow: 0 18px 50px rgba(15, 23, 42, 0.8);
        }

        .card h2 {
            font-size: 1.1rem;
            margin: 0 0 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
        }

        .pill {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 3px 8px;
            border-radius: 999px;
            border: 1px solid rgba(148, 163, 184, 0.4);
            color: var(--muted);
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }

        @media (max-width: 700px) {
            .grid-2, .grid-3 {
                grid-template-columns: minmax(0, 1fr);
            }
        }

        label {
            display: block;
            font-size: 0.8rem;
            margin-bottom: 4px;
            color: var(--muted);
        }

        .field {
            margin-bottom: 10px;
        }

        input[type="number"], input[type="text"], select {
            width: 100%;
            padding: 7px 9px;
            border-radius: 9px;
            border: 1px solid rgba(148, 163, 184, 0.6);
            background: rgba(15, 23, 42, 0.9);
            color: var(--text);
            font-size: 0.9rem;
            outline: none;
        }

        input[type="number"]:focus, input[type="text"]:focus, select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 1px rgba(56, 189, 248, 0.4);
        }

        .row-inline {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 4px 0 10px;
            font-size: 0.83rem;
            color: var(--muted);
        }

        .row-inline input[type="checkbox"] {
            width: 15px;
            height: 15px;
            accent-color: var(--accent);
        }

        .helper-note {
            font-size: 0.75rem;
            color: var(--muted);
            margin-top: 2px;
        }

        button {
            border: none;
            cursor: pointer;
            padding: 8px 14px;
            border-radius: 999px;
            background: linear-gradient(135deg, #0ea5e9, #22c55e);
            color: #0b1120;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 4px;
        }

        button.secondary {
            background: rgba(15, 23, 42, 0.9);
            color: var(--muted);
            border: 1px solid rgba(148, 163, 184, 0.5);
        }

        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            gap: 6px;
            margin-bottom: 10px;
        }

        .total-number {
            font-size: 1.4rem;
            font-weight: 700;
        }

        .total-number span {
            font-size: 0.8rem;
            color: var(--muted);
            margin-left: 4px;
        }

        .badge-green {
            font-size: 0.7rem;
            color: #4ade80;
            background: rgba(22, 163, 74, 0.18);
            padding: 3px 9px;
            border-radius: 999px;
            border: 1px solid rgba(34, 197, 94, 0.4);
        }

        .badge-red {
            font-size: 0.7rem;
            color: var(--danger);
            background: rgba(248, 113, 113, 0.12);
            padding: 3px 9px;
            border-radius: 999px;
            border: 1px solid rgba(248, 113, 113, 0.4);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8rem;
        }

        th, td {
            text-align: left;
            padding: 6px 6px;
        }

        th {
            font-weight: 600;
            color: var(--muted);
            border-bottom: 1px solid rgba(148, 163, 184, 0.5);
        }

        tr:nth-child(odd) td {
            background: rgba(15, 23, 42, 0.7);
        }

        tr:nth-child(even) td {
            background: rgba(15, 23, 42, 0.4);
        }

        tr td:last-child {
            text-align: right;
        }

        .section-title {
            font-size: 0.9rem;
            font-weight: 600;
            margin: 10px 0 4px;
        }

        .mini {
            font-size: 0.78rem;
            color: var(--muted);
        }

        .muted-box {
            font-size: 0.78rem;
            color: var(--muted);
            padding: 8px 9px;
            background: rgba(15, 23, 42, 0.9);
            border-radius: 9px;
            border: 1px dashed rgba(148, 163, 184, 0.6);
            margin-top: 8px;
        }

        .muted-box strong {
            color: #e5e7eb;
        }

        .footer-note {
            margin-top: 18px;
            font-size: 0.78rem;
            color: var(--muted);
            line-height: 1.5;
        }

        .footer-note strong {
            color: #e5e7eb;
        }
    </style>
</head>
<body>
<div class="page">
    <h1>
        <span class="logo"></span>
        Hedge Fee Calculator – XAUUSD
    </h1>
    <div class="subtitle">
        Bảng tính chi phí cho 1 lần <b>mở + đóng</b> lệnh, dùng cho cả lệnh đơn hoặc cặp hedge 2 tài khoản (TK1 & TK2).
    </div>

    <div class="layout">
        <!-- INPUT PANEL -->
        <div class="card">
            <h2>
                Thông số giao dịch
                <span class="pill">INPUT</span>
            </h2>

            <div class="field">
                <label for="symbol">Sản phẩm</label>
                <input id="symbol" type="text" value="XAUUSD"/>
                <div class="helper-note">
                    Ví dụ: XAUUSD. 1 lot vàng tiêu chuẩn thường = 100 oz.
                </div>
            </div>

            <div class="grid-3">
                <div class="field">
                    <label for="lotSize">Khối lượng (lot)</label>
                    <input id="lotSize" type="number" min="0" step="0.01" value="1"/>
                    <div class="helper-note">
                        Ví dụ: 0.5 – 1 – 2 lot.
                    </div>
                </div>

                <div class="field">
                    <label for="pipValue">Giá trị 1 pip / lot (USD)</label>
                    <input id="pipValue" type="number" min="0" step="0.01" value="1"/>
                    <div class="helper-note">
                        Với XAUUSD, 1 pip (0.01) ≈ 1 USD/lot trên đa số sàn.
                    </div>
                </div>

                <div class="field">
                    <label for="hedgeSides">Kiểu giao dịch</label>
                    <select id="hedgeSides">
                        <option value="1">1 lệnh đơn</option>
                        <option value="2" selected>Hedge 2 lệnh (TK1 & TK2)</option>
                    </select>
                    <div class="helper-note">
                        Hedge: 1 BUY + 1 SELL cùng lot.
                    </div>
                </div>
            </div>

            <div class="section-title">Spread & Commission</div>
            <div class="grid-2">
                <div class="field">
                    <label for="spreadPips">Spread (pip)</label>
                    <input id="spreadPips" type="number" min="0" step="0.01" value="0.3"/>
                    <div class="helper-note">
                        ECN vàng thường 0.1 – 0.4 pip (giá XAU).
                    </div>
                </div>
                <div class="field">
                    <label for="commissionSide">Commission / lot / mỗi bên (USD)</label>
                    <input id="commissionSide" type="number" min="0" step="0.01" value="7"/>
                    <div class="helper-note">
                        ECN metals thường ~7–15 USD/lot/round-turn → ≈ 3.5–7 mỗi bên.
                    </div>
                </div>
            </div>

            <div class="section-title">Swap & chi phí khác</div>
            <div class="grid-3">
                <div class="field">
                    <label for="holdDays">Giữ lệnh (ngày)</label>
                    <input id="holdDays" type="number" min="0" step="1" value="0"/>
                    <div class="helper-note">0 nếu không qua đêm.</div>
                </div>
                <div class="field">
                    <label for="swapPerLotPerDay">Swap / lot / ngày / mỗi bên (USD)</label>
                    <input id="swapPerLotPerDay" type="number" step="0.01" value="0"/>
                    <div class="helper-note">
                        Nhiều sàn: swap long vàng có thể âm khá mạnh (ví dụ -30 đến -50 USD/lot/ngày).
                    </div>
                </div>
                <div class="field">
                    <label for="otherFees">Chi phí khác (USD)</label>
                    <input id="otherFees" type="number" step="0.01" value="0"/>
                    <div class="helper-note">Ví dụ: phí cố định, mark-up, v.v.</div>
                </div>
            </div>

            <div class="section-title">Ước tính chuyển lợi nhuận về TK2</div>
            <div class="grid-2">
                <div class="field">
                    <label for="priceMove">Biến động giá (USD)</label>
                    <input id="priceMove" type="number" step="0.1" value="5"/>
                    <div class="helper-note">
                        Ví dụ: giá vàng đi 5 USD (từ 2400 → 2405).
                    </div>
                </div>
                <div class="field">
                    <label for="direction">Chiều ưu tiên TK2</label>
                    <select id="direction">
                        <option value="up">TK2 thắng khi giá tăng</option>
                        <option value="down">TK2 thắng khi giá giảm</option>
                    </select>
                    <div class="helper-note">
                        Thực tế chỉ ảnh hưởng lệnh BUY/SELL, phí không đổi.
                    </div>
                </div>
            </div>

            <button id="btnCalc">
                ▶ Tính phí & lợi nhuận
            </button>
            <button id="btnPresetECN" class="secondary">
                ECN vàng mẫu
            </button>

            <div class="muted-box">
                <strong>Lưu ý:</strong> Đây là công cụ ước tính lý thuyết. Kết quả thực tế có thể khác do
                <em>slippage, spread giãn, lỗi khớp lệnh, swap thay đổi theo ngày, v.v.</em>
            </div>
        </div>

        <!-- RESULT PANEL -->
        <div class="card">
            <h2>
                Kết quả & thống kê phí
                <span class="pill">OUTPUT</span>
            </h2>

            <div class="results-header">
                <div>
                    <div class="mini">Tổng chi phí cho 1 vòng giao dịch</div>
                    <div class="total-number" id="totalCostDisplay">0.00 <span>USD</span></div>
                </div>
                <div>
                    <div class="mini">Ước tính lợi nhuận chuyển về TK2 (sau phí)</div>
                    <div id="netToTk2Display" class="badge-green">0.00 USD</div>
                </div>
            </div>

            <div class="section-title">Chi tiết từng loại phí</div>
            <table>
                <thead>
                <tr>
                    <th>Khoản mục</th>
                    <th>Giải thích</th>
                    <th>Giá trị (USD)</th>
                </tr>
                </thead>
                <tbody id="breakdownBody">
                <!-- sẽ fill bằng JS -->
                </tbody>
            </table>

            <div class="section-title">Thông tin nhanh (theo input hiện tại)</div>
            <div class="mini" id="quickInfo"></div>

            <div class="section-title">Gợi ý tham khảo phí XAUUSD (mang tính ước lượng)</div>
            <ul class="mini">
                <li>ECN XAUUSD: spread thường <b>0.1–0.4 pip</b>, đôi khi thấp hơn khi thanh khoản
                    tốt.:contentReference[oaicite:0]{index=0}
                </li>
                <li>Commission metals: khoảng <b>7–15 USD/lot/round-turn</b> trên nhiều tài khoản ECN (tức mỗi bên 3.5–7
                    USD).:contentReference[oaicite:1]{index=1}
                </li>
                <li>1 lot vàng chuẩn = <b>100 oz</b>, 1 pip (0.01) ≈ <b>1 USD/lot</b> trên đa số
                    sàn.:contentReference[oaicite:2]{index=2}
                </li>
                <li>Swap long XAUUSD trên một số sàn có thể âm khá mạnh, ví dụ cỡ vài chục pip/ngày (≥ 20–40
                    USD/lot/ngày).:contentReference[oaicite:3]{index=3}
                </li>
            </ul>

            <div class="footer-note">
                <strong>Ý tưởng sử dụng:</strong> mỗi lần bạn muốn “dịch chuyển” 1 phần equity từ TK1 sang TK2
                bằng cách hedge 2 lệnh ngược nhau, hãy nhập lot, spread, commission, số ngày giữ lệnh → xem
                <b>tổng phí</b> cho 1 vòng giao dịch là bao nhiêu và <b>lợi nhuận ròng về TK2</b> với biên độ giá mong
                muốn.
            </div>
        </div>
    </div>
</div>

<script>
    function toNumber(id, defaultValue = 0) {
        const el = document.getElementById(id);
        if (!el) return defaultValue;
        const v = parseFloat(el.value.replace(",", "."));
        return isNaN(v) ? defaultValue : v;
    }

    function formatUSD(x) {
        return x.toFixed(2);
    }

    function calc() {
        const symbol = document.getElementById("symbol").value || "XAUUSD";
        const lot = toNumber("lotSize", 1);
        const pipValue = toNumber("pipValue", 1);
        const spreadPips = toNumber("spreadPips", 0);
        const commissionSide = toNumber("commissionSide", 0);
        const holdDays = toNumber("holdDays", 0);
        const swapPerLotPerDay = toNumber("swapPerLotPerDay", 0);
        const otherFees = toNumber("otherFees", 0);
        const priceMove = toNumber("priceMove", 0);
        const hedgeSides = parseInt(document.getElementById("hedgeSides").value, 10) || 1;

        // ----- Phí spread -----
        // Spread cost cho 1 bên: spread(pip) * pipValue(USD/pip/lot) * lot
        const spreadPerSide = spreadPips * pipValue * lot;
        const spreadTotal = spreadPerSide * hedgeSides;

        // ----- Commission -----
        // commissionSide = USD / lot / mỗi bên
        const commissionTotal = commissionSide * lot * hedgeSides;

        // ----- Swap -----
        // swapPerLotPerDay = USD / lot / ngày / mỗi bên
        const swapTotal = swapPerLotPerDay * lot * holdDays * hedgeSides;

        // ----- Other -----
        const otherTotal = otherFees; // nhập trực tiếp tổng

        const totalCost = spreadTotal + commissionTotal + swapTotal + otherTotal;

        document.getElementById("totalCostDisplay").textContent = formatUSD(totalCost) + " ";
        const span = document.createElement("span");
        span.textContent = "USD";
        document.getElementById("totalCostDisplay").appendChild(span);

        // ----- Ước tính lợi nhuận “chuyển về TK2” -----
        // Giả định: hedge 1:1, TK2 đi ĐÚNG hướng priceMove, TK1 ngược lại.
        // PnL TK2 = priceMove (USD) * 100 usd/1$ / lot? -> ta dựa trên pipValue:
        // 1 pip = 0.01, pipValue = USD/pip/lot => 1 USD giá = 100 pip => value = 100 * pipValue
        // PriceMove (USD) => pipsMove = priceMove / 0.01 = priceMove * 100
        const pipsMove = priceMove * 100;
        const pnlPerLotPerSide = pipsMove * pipValue; // USD / lot
        const pnlTk2Gross = pnlPerLotPerSide * lot;   // TK2 thắng
        const pnlTk1Gross = -pnlTk2Gross;             // TK1 thua đối xứng
        const netToTk2 = pnlTk2Gross - totalCost;     // Lãi về TK2 sau khi trả phí

        const netEl = document.getElementById("netToTk2Display");
        netEl.textContent = (netToTk2 >= 0 ? "+" : "") + formatUSD(netToTk2) + " USD";
        netEl.className = netToTk2 >= 0 ? "badge-green" : "badge-red";

        // ----- Fill breakdown table -----
        const body = document.getElementById("breakdownBody");
        body.innerHTML = "";

        const rows = [
            {
                label: "Spread",
                explain:
                    `Spread ${spreadPips} pip × ${pipValue} USD/pip × ${lot} lot × ${hedgeSides} bên`,
                value: spreadTotal
            },
            {
                label: "Commission",
                explain:
                    `${commissionSide} USD/lot/mỗi bên × ${lot} lot × ${hedgeSides} bên`,
                value: commissionTotal
            },
            {
                label: "Swap",
                explain:
                    `${swapPerLotPerDay} USD/lot/ngày/mỗi bên × ${lot} lot × ${holdDays} ngày × ${hedgeSides} bên`,
                value: swapTotal
            },
            {
                label: "Chi phí khác",
                explain: "Các chi phí cố định khác (nhập thẳng, không nhân sides)",
                value: otherTotal
            },
            {
                label: "TỔNG PHÍ",
                explain: "Tổng tất cả khoản phí cho 1 vòng mở + đóng",
                value: totalCost
            },
            {
                label: "Lợi nhuận gộp TK2",
                explain:
                    `Biến động giá ${priceMove} USD ⇒ ~${pipsMove.toFixed(0)} pip × ${pipValue} USD/pip × ${lot} lot`,
                value: pnlTk2Gross
            },
            {
                label: "Lợi nhuận ròng về TK2",
                explain: "Lợi nhuận TK2 sau khi trừ toàn bộ phí ở trên",
                value: netToTk2
            }
        ];

        rows.forEach(r => {
            const tr = document.createElement("tr");
            const td1 = document.createElement("td");
            td1.textContent = r.label;
            const td2 = document.createElement("td");
            td2.textContent = r.explain;
            const td3 = document.createElement("td");
            td3.textContent = formatUSD(r.value);
            tr.appendChild(td1);
            tr.appendChild(td2);
            tr.appendChild(td3);
            body.appendChild(tr);
        });

        // ----- Quick info -----
        const qi = document.getElementById("quickInfo");
        qi.textContent =
            `Giao dịch ${symbol} với ${lot} lot, spread ${spreadPips} pip, commission ${commissionSide} USD/lot/mỗi bên, `
            + `giữ lệnh ${holdDays} ngày với swap ${swapPerLotPerDay} USD/lot/ngày/mỗi bên. `
            + `Kiểu: ${hedgeSides === 2 ? "cặp hedge 2 lệnh (TK1 & TK2)" : "1 lệnh đơn"}.`;
    }

    function setPresetECN() {
        // Một số giá trị tham khảo cho ECN vàng XAUUSD
        document.getElementById("symbol").value = "XAUUSD";
        document.getElementById("lotSize").value = "1";
        document.getElementById("pipValue").value = "1";
        document.getElementById("hedgeSides").value = "2";
        document.getElementById("spreadPips").value = "0.2";
        document.getElementById("commissionSide").value = "4"; // ví dụ 8 USD/lot round-turn
        document.getElementById("holdDays").value = "0";
        document.getElementById("swapPerLotPerDay").value = "0";
        document.getElementById("otherFees").value = "0";
        document.getElementById("priceMove").value = "5";
        calc();
    }

    document.getElementById("btnCalc").addEventListener("click", function (e) {
        e.preventDefault();
        calc();
    });
    document.getElementById("btnPresetECN").addEventListener("click", function (e) {
        e.preventDefault();
        setPresetECN();
    });

    // Tính lần đầu với preset mặc định
    calc();
</script>
</body>
</html>
