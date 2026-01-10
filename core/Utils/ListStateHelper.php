<?php 
class ListStateHelper
{
    public static function remember(): void
    {
      $state = self::get();

      $state['page'] = $_GET['page'] ?? $state['page'] ?? 1;
      $state['sort'] = $_GET['sort'] ?? $state['sort'] ?? 'id';
      $state['dir']  = $_GET['dir']  ?? $state['dir']  ?? 'asc';
    
      if (isset($_GET['sort'])) {
          $state['sort'] = $_GET['sort'];
          $state['page'] = 1; // reset page on sort
      }

      if (isset($_GET['dir'])) {
          $state['dir'] = $_GET['dir'];
          $state['page'] = 1;
      }
      
      if (isset($_GET['page'])) {
          $state['page'] = (int) $_GET['page'];
      }

      Session::set('list_state', $state);
    }

    public static function get(): array
    {
        return Session::get('list_state', [
            'page' => 1,
            'sort' => 'name',
            'dir'  => 'asc',
        ]);
    }

    public static function url(string $path = '', array $override = []): string
    {
        $state = array_merge(self::get(), $override);
        return $path . '?' . http_build_query($state);
    }
    
    public static function hiddenInputs(): string
    {
        $state = self::get();
        $html = '';

        foreach ($state as $key => $value) {
            $html .= '<input type="hidden" name="' . htmlspecialchars($key) .
                     '" value="' . htmlspecialchars($value) . '">' . PHP_EOL;
        }

        return $html;
    }

    public static function reset()
    {
        Session::set('list_state', [
            'page' => 1,
            'sort' => 'id',
            'dir'  => 'asc',
        ]);
    }

}