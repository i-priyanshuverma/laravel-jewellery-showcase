# Automated Test Suite & Coverage Report

**Application:** Sonar Haat &mdash; Jewellery Showcase Platform  
**Testing Framework:** PHPUnit 11 / Laravel Test Suite  
**Database:** SQLite In-Memory Database (Isolated per test)  
**Total Tests:** 88 Passed  
**Total Assertions:** 305 Passed  
**Execution Duration:** ~2.4 seconds  

---

## 1. Test Suite Architecture

The test suite provides comprehensive behavioral, security, concurrency, and real-time coverage across 23 test classes:

| Domain | Test Class | Coverage Area |
|---|---|---|
| **Security & Headers** | `SecurityAndLocaleTest` | CSP, X-Content-Type-Options, Anti-Clickjacking, Timezone |
| **Internationalisation** | `SecurityAndLocaleTest` | English, Hindi (`hi`), Arabic RTL (`ar`), fallback |
| **High Concurrency** | `StockReservationServiceTest` | Row-level `lockForUpdate()`, hold expiry, race conditions |
| **Realtime WebSockets** | `RealtimeStockBroadcastTest` | Reverb public & private channel authorization |
| **Cascading State** | `CascadingReservationTest` | Vendor suspension, product/variant deletion hold cleanup |
| **Vendor Management** | `VendorManagementTest`, `VendorDashboardTest` | Approvals, suspensions, activations, metrics |
| **Catalogue & Search** | `CatalogueFilterTest`, `ProductSearchServiceTest` | Multi-parameter facet filtering, price/weight ranges |
| **CSV Ingress** | `CsvImportTest` | Chunked queue ingestion, error logging, auto-draft |
| **Studio Gallery** | `ProductImageManagementTest` | Multi-image uploads, thumbnail deletion, MIME checks |
| **Authentication & RBAC** | `AuthenticationTest`, `RegistrationTest`, `VendorRegistrationTest` | Guards, role middleware, email verification |

---

## 2. Test Execution Command

Run the complete test suite locally:
```bash
composer test
# or
php artisan test
```
