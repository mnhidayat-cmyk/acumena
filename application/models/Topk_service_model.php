<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Topk_service_model
 *
 * Tugas:
 * - Menghitung kombinasi pasangan SWOT (S-O, W-O, S-T, W-T)
 * - Menghasilkan Top-K pasangan dengan skor tertinggi
 * - Dipakai oleh endpoint: api/project/generating_top_k_pairs
 *
 * Catatan:
 * - Data sumber: tabel project_swot (via Swot_model)
 * - Hanya ambil item dengan is_deleted IS NULL
 * - Format output generik:
 *      x_... = faktor kiri (S atau W)
 *      y_... = faktor kanan (O atau T)
 */
class Topk_service_model extends CI_Model
{
    /** @var string */
    protected $swot_table = 'project_swot';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Swot_model', 'swot');
    }

    /**
     * Entry utama:
     * Ambil Top-K pairs untuk 1 project dan 1 pair_type.
     *
     * @param int    $project_id
     * @param string $pair_type  'S-O' | 'W-O' | 'S-T' | 'W-T'
     * @param int    $topK       jumlah maksimum pasangan yang dikembalikan
     * @return array             list pasangan, sudah di-sort desc by score
     */
    public function get_top_k_pairs_by_type($project_id, $pair_type, $topK = 12)
    {
        $project_id = (int)$project_id;
        $pair_type  = strtoupper(trim($pair_type));

        // Tentukan kategori kiri & kanan berdasarkan pair_type
        $cats = $this->resolve_categories($pair_type);
        if ($cats === null) {
            return []; // pair_type tidak valid
        }

        list($leftCat, $rightCat) = $cats;

        // Ambil faktor minimal untuk Top-K
        $leftItems  = $this->swot->get_minimal_for_topk($project_id, $leftCat);
        $rightItems = $this->swot->get_minimal_for_topk($project_id, $rightCat);

        if (empty($leftItems) || empty($rightItems)) {
            return [];
        }

        $pairs = [];

        // Hitung semua kombinasi left x right
        foreach ($leftItems as $L) {
            $wL = $this->toFloat($L['weight'] ?? 0);
            $rL = $this->toFloat($L['rating'] ?? 0);

            foreach ($rightItems as $R) {
                $wR = $this->toFloat($R['weight'] ?? 0);
                $rR = $this->toFloat($R['rating'] ?? 0);

                // Jika ada weight/rating kosong, dianggap 0 => skor 0
                $score = ($wL * $rL) * ($wR * $rR);

                // Lewatkan yang benar-benar 0 supaya list lebih bersih
                if ($score <= 0) {
                    continue;
                }

                $pairs[] = [
                    // Faktor kiri (x_)
                    'x_id'          => (int)$L['id'],
                    'x_category'    => $leftCat,
                    'x_description' => (string)$L['description'],
                    'x_weight'      => $wL,
                    'x_rating'      => $rL,

                    // Faktor kanan (y_)
                    'y_id'          => (int)$R['id'],
                    'y_category'    => $rightCat,
                    'y_description' => (string)$R['description'],
                    'y_weight'      => $this->toFloat($R['weight'] ?? 0),
                    'y_rating'      => $this->toFloat($R['rating'] ?? 0),

                    // Skor kombinasi
                    'score'         => round($score, 4),
                ];
            }
        }

        if (empty($pairs)) {
            return [];
        }

        // Urutkan desc by score
        usort($pairs, function ($a, $b) {
            if ($a['score'] == $b['score']) return 0;
            return ($a['score'] < $b['score']) ? 1 : -1;
        });

        // Ambil Top-K
        $pairs = array_slice($pairs, 0, $topK);

        return $pairs;
    }

    /**
     * Resolve kategori kiri & kanan berdasarkan pair_type.
     *
     * 'S-O' => ['S','O']
     * 'W-O' => ['W','O']
     * 'S-T' => ['S','T']
     * 'W-T' => ['W','T']
     *
     * @param string $pair_type
     * @return array|null
     */
    private function resolve_categories($pair_type)
    {
        switch ($pair_type) {
            case 'S-O':
                return ['S', 'O'];
            case 'W-O':
                return ['W', 'O'];
            case 'S-T':
                return ['S', 'T'];
            case 'W-T':
                return ['W', 'T'];
            default:
                return null;
        }
    }

    /**
     * Helper: aman konversi ke float (null / '' => 0.0)
     */
    private function toFloat($val)
    {
        if ($val === null || $val === '') {
            return 0.0;
        }
        return (float)$val;
    }
}
