<?php

namespace App\Model;

class ThemeManager extends AbstractManager
{
    public const TABLE = 'theme';

    /**
     * Get all row from database.
     */
    public function selectThemesByCategoryIdWithPostInfos(
        int $idCategory,
        string $orderBy = '',
        string $direction = 'ASC'
    ): array {
        $query = 'SELECT th.id as id_theme,
        th.name as name_theme,
        ca.name as name_category,
        count(po.id) as number_post,
        max(po.date) as last_date_post 
        FROM post as po RIGHT JOIN ' . static::TABLE . ' as th ON po.theme_id = th.id 
        JOIN category as ca ON th.category_id = ca.id
        WHERE ca.id = :idcategory GROUP BY th.id;';
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }
        $statement = $this->pdo->prepare($query);

        $statement->bindValue(':idcategory', $idCategory, \PDO::PARAM_INT);

        $statement->execute();

        return $statement->fetchAll();
    }

    public function selectThemesBycategoryId(int $categoryId)
    {
        $query = "SELECT * FROM theme t WHERE t.category_id=:categoryId;";

        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':categoryId', $categoryId, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }


    public function selectCategoryIdFromTheme(int $themeId)
    {
        $query = "SELECT category_id FROM theme WHERE id=:themeId;";

        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':themeId', $themeId, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }
}
