# ⚡ QUICK START: TEST GENERATE RECOMMENDATIONS

**Time:** ~10 minutes

---

## 🧪 TESTING DENGAN POSTMAN

### Step 1: Test GET Endpoint untuk S-O Quadrant

**Method:** GET  
**URL:** `http://acumena.test/api/project/get_recommendation_strategy?quadrant=S-O`

**Headers:**

```
Content-Type: application/json
```

**Expected Response (HTTP 200):**

```json
{
	"success": true,
	"message": "Recommendation strategy retrieved successfully",
	"data": {
		"quadrant": "S-O",
		"strategy": "Manfaatkan kekuatan untuk merebut peluang pasar",
		"description": "Develop new products based on market opportunities",
		"recommendation": "Focus on innovation and expansion"
	}
}
```

✅ **Jika response 200 → Endpoint WORKING**

---

### Step 2: Test untuk S-T Quadrant

**Method:** GET  
**URL:** `http://acumena.test/api/project/get_recommendation_strategy?quadrant=S-T`

**Expected Response:** Recommendations untuk S-T

---

### Step 3: Test untuk W-O Quadrant

**Method:** GET  
**URL:** `http://acumena.test/api/project/get_recommendation_strategy?quadrant=W-O`

---

### Step 4: Test untuk W-T Quadrant

**Method:** GET  
**URL:** `http://acumena.test/api/project/get_recommendation_strategy?quadrant=W-T`

---

## 🎨 TESTING DARI UI

### Step 1: Buka Project

1. Buka URL: `http://acumena.test/project?uuid=YOUR-UUID&step=matrix`
2. Pastikan sudah login
3. Lihat section "Prioritized Strategies"

### Step 2: Klik "Generate Recommendations" Button

1. Klik tombol biru "Generate Recommendations"
2. Tunggu loading state
   - Button disabled
   - Text berubah "Generating..."
3. Tunggu response dari server

### Step 3: Lihat Hasil

Alert akan muncul dengan format:

```
RECOMMENDED STRATEGIES:

S-O:
  1. Manfaatkan kekuatan untuk merebut peluang pasar

S-T:
  1. Gunakan kekuatan untuk menghadapi ancaman

W-O:
  1. Perbaiki kelemahan untuk memanfaatkan peluang

W-T:
  1. Mitigasi kelemahan untuk hindari ancaman
```

### Step 4: Verifikasi

- ✅ Alert muncul dengan recommendations
- ✅ Ada recommendations untuk S-O, S-T, W-O, W-T
- ✅ Button kembali ke state normal
- ✅ Tidak ada error di console (F12 → Console)

---

## 🐛 TROUBLESHOOTING

### Error 1: Button click tidak trigger

```
Cause: JavaScript error atau element tidak ditemukan
Fix:
  1. Open console (F12)
  2. Check untuk error messages
  3. Verify element ID "generateRecommendationsBtn" ada di HTML
```

### Error 2: Empty alert (tidak ada recommendations)

```
Cause: API return empty data
Fix:
  1. Check database: SELECT * FROM matrix_ie_quadrant_strategies
  2. Verify data ada untuk semua quadrants
  3. If empty, insert sample data:
     INSERT INTO matrix_ie_quadrant_strategies
     (quadrant, strategy_statement, description, recommendation)
     VALUES ('S-O', 'Strategy text...', 'Description...', 'Recommendation...');
```

### Error 3: "Method not allowed" (405)

```
Cause: Wrong HTTP method
Fix: Ensure GET method, not POST
```

### Error 4: "Quadrant is required" (400)

```
Cause: Missing quadrant parameter
Fix: Add ?quadrant=S-O to URL
```

### Error 5: Network error / Cannot reach endpoint

```
Cause:
  1. Server not running
  2. Wrong URL
  3. API base URL wrong
Fix:
  1. Check if Laravel/PHP running
  2. Verify base_url in config/database.php
  3. Check console untuk exact error URL
```

---

## 📋 TESTING CHECKLIST

### API Testing

- [ ] **GET S-O** - Endpoint return 200 + data
- [ ] **GET S-T** - Endpoint return 200 + data
- [ ] **GET W-O** - Endpoint return 200 + data
- [ ] **GET W-T** - Endpoint return 200 + data
- [ ] **Invalid quadrant** - Return 400 "Quadrant is required"
- [ ] **Missing quadrant** - Return 400 error

### UI Testing

- [ ] **Button visible** - "Generate Recommendations" button ada di page
- [ ] **Button clickable** - Can click button
- [ ] **Loading state** - Button disabled, text change during loading
- [ ] **Success response** - Alert muncul dengan recommendations
- [ ] **Error handling** - Error message jika API fail
- [ ] **Button reset** - Button back to normal after complete

### Data Testing

- [ ] **Recommendations exist** - Data ada untuk S-O, S-T, W-O, W-T
- [ ] **Data format** - Response format valid JSON
- [ ] **Message clarity** - Recommendations jelas & informative

---

## 📊 EXPECTED DATA IN ALERTS

### S-O (Strengths-Opportunities)

```
Gunakan kekuatan untuk merebut peluang pasar
```

### S-T (Strengths-Threats)

```
Gunakan kekuatan untuk menghadapi ancaman kompetitor
```

### W-O (Weaknesses-Opportunities)

```
Perbaiki kelemahan untuk memanfaatkan peluang
```

### W-T (Weaknesses-Threats)

```
Mitigasi kelemahan untuk hindari ancaman
```

---

## ✅ VERIFICATION SCRIPT

Run these commands to verify everything working:

```sql
-- Check if matrix_ie_quadrant_strategies has data
SELECT COUNT(*) as total, quadrant, COUNT(DISTINCT quadrant) as quadrant_count
FROM matrix_ie_quadrant_strategies
GROUP BY quadrant;

-- Should return 4 rows (one per quadrant)

-- Check specific quadrant
SELECT * FROM matrix_ie_quadrant_strategies
WHERE quadrant = 'S-O' LIMIT 1;
```

---

## 🎯 EXPECTED RESULTS

✅ When everything works:

```
1. Click "Generate Recommendations" button
   ↓
2. Loading state shows (button disabled, "Generating...")
   ↓
3. Alert appears with recommendations for S-O, S-T, W-O, W-T
   ↓
4. Click OK to dismiss
   ↓
5. Button back to normal, ready to click again
```

---

**Total Test Time:** ~10-15 minutes

**If all tests pass:** ✅ GENERATE RECOMMENDATIONS WORKING!

Generated: 12 Desember 2025
