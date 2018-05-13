#### get Pivot table

```graphql
query jobsQuery {
  jobs(first: 10) {
    edges {
      node {
        user {
          name
          tasks(count: 5) {
            data {
              title

              pivot {
                pivot_field
              }
            }
          }
        }
      }
    }
  }
}
```
