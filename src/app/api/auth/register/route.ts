import { NextRequest, NextResponse } from 'next/server';
import { dbQuery } from '../../../../lib/db';
import type { User } from '../../../../lib/types';

export async function POST(request: NextRequest) {
  try {
    const { name, email, password } = await request.json();
    if (!name || !email || !password) {
      return NextResponse.json({ error: 'Name, email, and password are required' }, { status: 400 });
    }

    // Check if email already exists
    const existing = await dbQuery<{ id: number }>('SELECT id FROM users WHERE email = $1', [email]);
    if (existing.length > 0) {
      return NextResponse.json({ error: 'Email already exists' }, { status: 400 });
    }

    // Insert new user
    const result = await dbQuery<{ id: number }>(
      "INSERT INTO users (name, email, password, role) VALUES ($1, $2, $3, 'customer') RETURNING id",
      [name, email, password]
    );

    const newId = result[0].id;

    return NextResponse.json({
      id: newId,
      name,
      email,
      role: 'customer'
    });
  } catch (error: any) {
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}
