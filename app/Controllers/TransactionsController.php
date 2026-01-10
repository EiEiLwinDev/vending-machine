<?php

  class TransactionsController extends Controller {
    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function index()
      {
        adminOnly();
        if (!isset($_GET['page'])) {
          ListStateHelper::reset();
        }
        // Save state only if query params exist
        ListStateHelper::remember();

        // Read state ONLY from helper
        $state = ListStateHelper::get();

        $page  = max(1, (int) $state['page']);
        $limit = (int) Env::get('PAGINATION_LIMIT', 10);
        $offset = ($page - 1) * $limit;

        $allowedSorts = ['p.name', 'price', 'quantity', 'total'];
        $sort = in_array($state['sort'], $allowedSorts)
            ? $state['sort']
            : 'p.name';

        $dir = in_array($state['dir'], ['asc', 'desc'])
            ? $state['dir']
            : 'asc';

        // Total count
        $total = $this->db->query("SELECT COUNT(*) FROM transactions")->fetchColumn();
        $totalPages = (int) ceil($total / $limit);

        // Fetch data
        $stmt = $this->db->prepare("
            SELECT 
            p.name As product, 
            t.quantity, 
            t.unit_price As price, 
            t.total_price As total 
            FROM transactions t 
            LEFT JOIN products p on t.product_id = p.id 
            ORDER BY {$sort} {$dir}
            LIMIT :limit OFFSET :offset
        ");

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $transactions = $stmt->fetchAll();

        $stmRevenue = $this->db->prepare("
          SELECT SUM(t.total_price) As revenue
          FROM transactions t 
          INNER JOIN products p on t.product_id = p.id 
        ");
        $stmRevenue->execute();
        $revenue = $stmRevenue->fetchColumn();

        $this->render('transactions/index', [
            'transactions'   => $transactions,
            'page'       => $page,
            'limit'      => $limit,
            'totalCount' => $total,
            'totalPages' => $totalPages,
            'sort'       => $sort,
            'dir'        => $dir,
            'revenue' => $revenue
        ]);
      }
  }

?>