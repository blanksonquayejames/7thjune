import { NextResponse } from 'next/server';
import { dbQuery } from '../../../../lib/db';
import type { User } from '../../../../lib/types';

// GET: Retrieve all users (excluding passwords) for Admin Panel
export async function GET() {
  try {
    const users = await dbQuery<User>('SELECT id, name, email, role, created_at FROM users ORDER BY id ASC');
    return NextResponse.json(users);
  } catch (error: any) {
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}
