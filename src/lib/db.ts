import { Pool, type PoolClient } from 'pg';

let pool: Pool;

// Prevent multiple pool instances in Next.js development hot-reloading
const globalForPool = global as unknown as { _postgresPool?: Pool };

if (!globalForPool._postgresPool) {
  globalForPool._postgresPool = new Pool({
    connectionString: process.env.DATABASE_URL || 'postgresql://postgres:postgres@localhost:5432/seventh_june_computers',
  });
}

pool = globalForPool._postgresPool;

/**
 * Helper to run query with parameters
 */
export async function dbQuery<T = any>(sql: string, params: any[] = []): Promise<T[]> {
  try {
    const result = await pool.query(sql, params);
    return result.rows as T[];
  } catch (error) {
    console.error('Database Query Error:', { sql, params, error });
    throw error;
  }
}

/**
 * Execute transactions safely
 */
export async function dbTransaction<T>(
  callback: (client: PoolClient) => Promise<T>
): Promise<T> {
  const client = await pool.connect();
  try {
    await client.query('BEGIN');
    const result = await callback(client);
    await client.query('COMMIT');
    return result;
  } catch (error) {
    await client.query('ROLLBACK');
    throw error;
  } finally {
    client.release();
  }
}
export { pool };
