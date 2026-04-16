# Travel Destinations API

## Endpoint

`GET /api/travel-destinations`

## Query parameters

- `search` (string, optional) - otsib väljadest `title`, `description`, `country`
- `country` (string, optional) - filtreerib riigi järgi
- `best_season` (string, optional) - filtreerib hooaja järgi
- `sort` (string, optional) - lubatud: `title`, `country`, `best_season`, `created_at`
- `direction` (string, optional) - `asc` või `desc`
- `limit` (integer, optional) - min 1, max 100, vaikimisi 20

## Example

`/api/travel-destinations?search=Barcelona&country=Spain&sort=title&direction=asc&limit=10`

## Response shape

```json
{
  "data": [
    {
      "id": 1,
      "title": "Barcelona",
      "image": "https://...",
      "description": "...",
      "country": "Spain",
      "best_season": "Spring",
      "created_at": "2026-04-16T12:00:00.000000Z",
      "updated_at": "2026-04-16T12:00:00.000000Z"
    }
  ],
  "meta": {
    "search": "Barcelona",
    "country": "Spain",
    "best_season": null,
    "sort": "title",
    "direction": "asc",
    "limit": 10,
    "count": 1,
    "cached_for_seconds": 600
  }
}
```

## Caching

API tulemused cache'itakse 10 minutiks (`600` sekundit), kasutades parameetritest koostatud võtmeid.
