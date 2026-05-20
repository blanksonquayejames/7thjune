import mysql from 'mysql2/promise';

let pool;

if (!global._mysqlPool) {
  global._mysqlPool = mysql.createPool({
    host: process.env.DB_HOST || 'localhost',
    user: process.env.DB_USER || 'root',
    password: process.env.DB_PASSWORD || '',
    database: process.env.DB_NAME || 'seventh_june_computers',
    port: Number(process.env.DB_PORT) || 3306,
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0,
    enableKeepAlive: true,
    keepAliveInitialDelay: 0
  });
}

pool = global._mysqlPool;

/**
 * Helper to run query with parameters
 * @param {string} sql 
 * @param {any[]} params 
 * @returns {Promise<any>}
 */
export async function dbQuery(sql, params = []) {
  try {
    const [results] = await pool.execute(sql, params);
    return results;
  } catch (error) {
    console.error('Database Query Error:', { sql, params, error });
    throw error;
  }
}

/**
 * Execute transactions safely
 * @param {function(any): Promise<any>} callback - async function(connection)
 */
export async function dbTransaction(callback) {
  const connection = await pool.getConnection();
  await connection.beginTransaction();
  try {
    const result = await callback(connection);
    await connection.commit();
    return result;
  } catch (error) {
    await connection.rollback();
    throw error;
  } finally {
    connection.release();
  }
}
