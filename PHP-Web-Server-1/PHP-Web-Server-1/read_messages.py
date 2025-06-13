
import json
import os
from datetime import datetime

def read_json_messages():
    """读取submissions.json文件中的message数据并存储为item id格式"""
    
    # 检查JSON文件是否存在
    if not os.path.exists('submissions.json'):
        print("submissions.json 文件不存在")
        return
    
    try:
        # 读取JSON文件
        with open('submissions.json', 'r', encoding='utf-8') as file:
            submissions = json.load(file)
        
        # 创建items字典来存储message数据
        items = {}
        
        print("正在处理消息数据...\n")
        
        # 遍历所有提交记录
        for index, submission in enumerate(submissions):
            item_id = f"item_{index + 1:03d}"  # 创建格式化的item ID (item_001, item_002等)
            
            # 提取message数据和相关信息
            items[item_id] = {
                "message": submission.get('message', ''),
                "user_id": submission.get('user_id', ''),
                "name": submission.get('name', ''),
                "email": submission.get('email', ''),
                "timestamp": submission.get('timestamp', ''),
                "processed_at": datetime.now().strftime('%Y-%m-%d %H:%M:%S')
            }
            
            # 显示处理结果
            print(f"{item_id}:")
            print(f"  Message: {submission.get('message', '')}")
            print(f"  User ID: {submission.get('user_id', '')}")
            print(f"  Name: {submission.get('name', '')}")
            print(f"  Email: {submission.get('email', '')}")
            print(f"  Timestamp: {submission.get('timestamp', '')}")
            print("-" * 50)
        
        # 将处理后的数据保存到新的JSON文件
        output_file = 'message_items.json'
        with open(output_file, 'w', encoding='utf-8') as file:
            json.dump(items, file, ensure_ascii=False, indent=2)
        
        print(f"\n✅ 成功处理了 {len(items)} 条消息记录")
        print(f"📁 数据已保存到: {output_file}")
        
        # 显示统计信息
        print(f"\n📊 统计信息:")
        print(f"   总记录数: {len(items)}")
        print(f"   输出文件: {output_file}")
        
        return items
        
    except json.JSONDecodeError as e:
        print(f"❌ JSON文件格式错误: {e}")
    except Exception as e:
        print(f"❌ 处理文件时发生错误: {e}")

def display_item_summary():
    """显示item摘要信息"""
    try:
        if os.path.exists('message_items.json'):
            with open('message_items.json', 'r', encoding='utf-8') as file:
                items = json.load(file)
            
            print("\n📋 Item ID 摘要:")
            print("=" * 60)
            for item_id, data in items.items():
                message_preview = data['message'][:30] + "..." if len(data['message']) > 30 else data['message']
                print(f"{item_id}: {message_preview} (来自: {data['name']})")
            print("=" * 60)
    except Exception as e:
        print(f"❌ 读取摘要时发生错误: {e}")

if __name__ == "__main__":
    print("🔄 开始读取JSON消息数据...\n")
    
    # 读取并处理消息数据
    result = read_json_messages()
    
    if result:
        # 显示摘要
        display_item_summary()
        
        print("\n✨ 处理完成!")
        print("💡 您可以查看 'message_items.json' 文件来查看所有处理后的数据")
